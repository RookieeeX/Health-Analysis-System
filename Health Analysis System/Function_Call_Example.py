from HealthAnalysisFuctionTable import SentimentRecognition, HeartRateAnalyzer

if __name__ == "__main__":
    #检验情绪分析部分代码
    SentimentRecognition.excute_emotion_recognition()

    #检验心率部分代码
    heart_rate = HeartRateAnalyzer.get_user_input()
    suggestion = HeartRateAnalyzer.analyze_heart_rate(heart_rate)
    print(suggestion)

