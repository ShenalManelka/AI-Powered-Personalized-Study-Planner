# ============================================================
# Personalized Recommendation Engine
# ============================================================

def generate_recommendations(
    study_hours,
    attendance,
    sleep_hours,
    internet_usage,
    assignments_completed,
    previous_score,
    predicted_score,
    risk_level,
    cluster
):

    recommendations = []


    # ========================================================
    # 1. Study Hours
    # ========================================================

    if study_hours < 5:

        recommendations.append(
            "Increase focused study time gradually and follow a consistent daily study routine."
        )

    elif study_hours < 8:

        recommendations.append(
            "Consider increasing focused study time to improve academic performance."
        )

    else:

        recommendations.append(
            "Maintain your current study routine while focusing on study quality."
        )


    # ========================================================
    # 2. Attendance
    # ========================================================

    if attendance < 60:

        recommendations.append(
            "Improve class attendance because regular participation can support academic progress."
        )

    elif attendance < 75:

        recommendations.append(
            "Try to improve attendance and avoid missing important lectures and practical sessions."
        )

    else:

        recommendations.append(
            "Maintain your current attendance level."
        )


    # ========================================================
    # 3. Sleep
    # ========================================================

    if sleep_hours < 6:

        recommendations.append(
            "Improve your sleep routine and aim for more consistent sleep to support concentration."
        )

    elif sleep_hours < 7:

        recommendations.append(
            "Try to maintain a more consistent sleep schedule."
        )

    else:

        recommendations.append(
            "Maintain your current healthy sleep routine."
        )


    # ========================================================
    # 4. Internet Usage
    # ========================================================

    if internet_usage > 8:

        recommendations.append(
            "Reduce unnecessary internet usage during study periods and use focused study sessions."
        )

    elif internet_usage > 6:

        recommendations.append(
            "Monitor recreational internet usage and minimize distractions while studying."
        )


    # ========================================================
    # 5. Assignments
    # ========================================================

    if assignments_completed < 3:

        recommendations.append(
            "Prioritize completing pending assignments and break large tasks into smaller activities."
        )

    elif assignments_completed < 6:

        recommendations.append(
            "Try to complete assignments consistently before their deadlines."
        )

    else:

        recommendations.append(
            "Continue maintaining your assignment completion habits."
        )


    # ========================================================
    # 6. Previous Academic Performance
    # ========================================================

    if previous_score < 50:

        recommendations.append(
            "Focus on improving weak academic areas and review previous mistakes."
        )

    elif previous_score < 65:

        recommendations.append(
            "Use regular revision and practice exercises to improve your previous academic performance."
        )

    else:

        recommendations.append(
            "Continue your current academic approach and challenge yourself with higher-level practice."
        )


    # ========================================================
    # 7. Predicted Performance
    # ========================================================

    if predicted_score < 50:

        recommendations.append(
            "Your predicted performance is low. Create a structured study plan and prioritize difficult subjects."
        )

    elif predicted_score < 65:

        recommendations.append(
            "Your predicted performance indicates room for improvement. Increase revision and practice time."
        )

    elif predicted_score < 80:

        recommendations.append(
            "Your predicted performance is moderate. Consistent revision can help you achieve a higher score."
        )

    else:

        recommendations.append(
            "Your predicted performance is strong. Maintain your study habits and focus on continuous improvement."
        )


    # ========================================================
    # 8. Academic Risk
    # ========================================================

    risk = str(risk_level).lower()

    if risk == "high":

        recommendations.append(
            "High academic risk detected. Give priority to weak subjects, assignments, attendance and regular revision."
        )

    elif risk == "medium":

        recommendations.append(
            "Medium academic risk detected. Follow a consistent study schedule and monitor your academic progress."
        )

    else:

        recommendations.append(
            "Low academic risk detected. Continue your current study habits and monitor your progress."
        )


    # ========================================================
    # 9. Student Cluster
    # ========================================================

    if int(cluster) == 0:

        recommendations.append(
            "Your student profile belongs to the academic support group. Focus on improving previous performance and maintaining consistent study habits."
        )

    else:

        recommendations.append(
            "Your student profile belongs to the higher academic performance group. Maintain your current habits and continue improving your academic goals."
        )


    # ========================================================
    # 10. Final Recommendation
    # ========================================================

    if risk == "high":

        priority = "High Priority"

    elif risk == "medium":

        priority = "Medium Priority"

    else:

        priority = "Normal Priority"


    return {
        "priority": priority,
        "recommendations": recommendations
    }


# ============================================================
# Test the Recommendation Engine
# ============================================================

if __name__ == "__main__":

    result = generate_recommendations(

        study_hours=8,
        attendance=60,
        sleep_hours=5,
        internet_usage=8,
        assignments_completed=3,
        previous_score=50,
        predicted_score=65,
        risk_level="Medium",
        cluster=0
    )

    print("\nPersonalized Recommendation Result")
    print("====================================")

    print("Priority:")
    print(result["priority"])

    print("\nRecommendations:")

    for number, recommendation in enumerate(
        result["recommendations"],
        start=1
    ):

        print(f"{number}. {recommendation}")