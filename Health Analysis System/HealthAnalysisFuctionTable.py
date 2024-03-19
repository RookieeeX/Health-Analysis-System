import json
import urllib.request
import urllib.error
import time
import os


class SentimentRecognition:
    image_folder = r"input_image"
    @classmethod
    def get_latest_image_path(cls):
        # Check if the folder exists
        if not os.path.exists(cls.image_folder):
            print(f"The folder '{cls.image_folder}' does not exist.")
            return None

        # Get all files in the folder and its subfolders ending with ".png"
        image_paths = [os.path.join(root, f) for root, _, files in os.walk(cls.image_folder)
                       for f in files if f.endswith(".png")]

        # Check if there are any PNG images found
        if not image_paths:
            print("No PNG images found in the specified folder.")
            return None

        # Get the path of the latest image based on modification time
        latest_image_path = max(image_paths, key=os.path.getmtime)
        return latest_image_path

    @classmethod
    def excute_emotion_recognition(cls):
        http_url = 'https://api-cn.faceplusplus.com/facepp/v3/detect'
        key = "2pg0jLoPER9RKtwc_lJXRdcwtH_q_LBD"
        secret = "OvdBP-2oPBQxcpWwNYxbVbYu4-tjxflz"
        filepath = SentimentRecognition.get_latest_image_path()

        boundary = '----------%s' % hex(int(time.time() * 1000))
        data = []
        data.append('--%s' % boundary)
        data.append('Content-Disposition: form-data; name="%s"\r\n' % 'api_key')
        data.append(key)
        data.append('--%s' % boundary)
        data.append('Content-Disposition: form-data; name="%s"\r\n' % 'api_secret')
        data.append(secret)
        data.append('--%s' % boundary)
        fr = open(filepath, 'rb')
        data.append('Content-Disposition: form-data; name="%s"; filename=" "' % 'image_file')
        data.append('Content-Type: %s\r\n' % 'application/octet-stream')
        data.append(fr.read())
        fr.close()
        data.append('--%s' % boundary)
        data.append('Content-Disposition: form-data; name="%s"\r\n' % 'return_landmark')
        data.append('1')
        data.append('--%s' % boundary)
        data.append('Content-Disposition: form-data; name="%s"\r\n' % 'return_attributes')
        data.append(
            "gender,age,smiling,headpose,facequality,blur,eyestatus,emotion,ethnicity,beauty,mouthstatus,eyegaze,skinstatus")
        data.append('--%s--\r\n' % boundary)

        for i, d in enumerate(data):
            if isinstance(d, str):
                data[i] = d.encode('utf-8')

        http_body = b'\r\n'.join(data)

        # build http request
        req = urllib.request.Request(url=http_url, data=http_body)

        # header
        req.add_header('Content-Type', 'multipart/form-data; boundary=%s' % boundary)

        try:
            # post data to server
            resp = urllib.request.urlopen(req, timeout=5)
            # get response
            qrcont = resp.read()
            # decode JSON response
            result = json.loads(qrcont.decode('utf-8'))

            # extract face attributes
            faces = result['faces']
            for face in faces:
                attributes = face['attributes']
                gender = attributes['gender']['value']
                age = attributes['age']['value']
                emotion_details = attributes['emotion']
                emotion = max(attributes['emotion'], key=attributes['emotion'].get)

                # print("Gender:", gender)
                # print("Age:", age)
                print("Emotion values:", emotion)
                for emotion_type, value in emotion_details.items():
                    print(emotion_type.capitalize() + ":", value)

        except urllib.error.HTTPError as e:
            print(e.read().decode('utf-8'))


class HeartRateAnalyzer:
    @staticmethod
    def analyze_heart_rate(heart_rate):
        if heart_rate < 60:
            return "Your heart rate is below normal. Please consult a doctor."
        elif 60 <= heart_rate <= 100:
            return "Your heart rate is within the normal range."
        else:
            return "Your heart rate is above normal. Please consider relaxing or doing some physical activity."

    @staticmethod
    def get_user_input():
        try:
            heart_rate = int(input("Enter your heart rate: "))
            if heart_rate < 0:
                raise ValueError("Heart rate cannot be negative.")
            return heart_rate
        except ValueError as e:
            print("Invalid input. Please enter a valid positive integer.")