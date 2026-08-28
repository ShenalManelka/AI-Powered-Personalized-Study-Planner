from flask import Flask, request, jsonify
import joblib
import pandas as pd
import sys
import os


app = Flask(__name__)


# ============================================================
# Recommendation Engine Import
# ============================================================

sys.path.append(
    os.path.join(
        os.path.dirname(__file__),
        "recommendation"
    )
)

# pyrefly: ignore [missing-import]
from recommendation_engine import generate_recommendations


# ============================================================
# Load trained models
# ============================================================

PERFORMANCE_MODEL_PATH = "models/performance_model.pkl"
PERFORMANCE_SCALER_PATH = "models/performance_scaler.pkl"

RISK_MODEL_PATH = "models/risk_model.pkl"

CLUSTER_MODEL_PATH = "models/cluster_model.pkl"
CLUSTER_SCALER_PATH = "models/cluster_scaler.pkl"


# ------------------------------------------------------------
# Performance Prediction Model
# ------------------------------------------------------------

performance_model = joblib.load(
    PERFORMANCE_MODEL_PATH
)

performance_scaler = joblib.load(
    PERFORMANCE_SCALER_PATH
)


# ------------------------------------------------------------
# Risk Prediction Model
# ------------------------------------------------------------

risk_model = joblib.load(
    RISK_MODEL_PATH
)


# ------------------------------------------------------------
# K-Means Clustering Model
# ------------------------------------------------------------

cluster_model = joblib.load(
    CLUSTER_MODEL_PATH
)

cluster_scaler = joblib.load(
    CLUSTER_SCALER_PATH
)


print("Performance model loaded successfully.")
print("Performance scaler loaded successfully.")
print("Risk model loaded successfully.")
print("Cluster model loaded successfully.")
print("Cluster scaler loaded successfully.")
print("Recommendation engine loaded successfully.")


# ============================================================
# Health Check
# ============================================================

@app.route("/", methods=["GET"])
def home():

    return jsonify({
        "status": "success",
        "message": "AI Study Planner API is running"
    })


# ============================================================
# Performance Prediction
# ============================================================

@app.route("/predict/performance", methods=["POST"])
def predict_performance():

    try:

        data = request.get_json()

        required_features = [
            "study_hours",
            "attendance",
            "sleep_hours",
            "internet_usage",
            "assignments_completed",
            "previous_score"
        ]

        for feature in required_features:

            if feature not in data:

                return jsonify({
                    "status": "error",
                    "message": f"Missing required field: {feature}"
                }), 400

        student = pd.DataFrame([{
            "study_hours": data["study_hours"],
            "attendance": data["attendance"],
            "sleep_hours": data["sleep_hours"],
            "internet_usage": data["internet_usage"],
            "assignments_completed": data["assignments_completed"],
            "previous_score": data["previous_score"]
        }])

        # Scale data using the same scaler used during training
        student_scaled = performance_scaler.transform(student)

        # Neural Network prediction
        prediction = performance_model.predict(
            student_scaled
        )[0]

        # Keep prediction between 0 and 100
        prediction = max(
            0,
            min(100, prediction)
        )

        return jsonify({
            "status": "success",
            "predicted_exam_score": round(
                float(prediction),
                2
            )
        })

    except Exception as e:

        return jsonify({
            "status": "error",
            "message": str(e)
        }), 500


# ============================================================
# Academic Risk Prediction
# ============================================================

@app.route("/predict/risk", methods=["POST"])
def predict_risk():

    try:

        data = request.get_json()

        required_features = [
            "study_hours",
            "attendance",
            "sleep_hours",
            "internet_usage",
            "assignments_completed",
            "previous_score"
        ]

        for feature in required_features:

            if feature not in data:

                return jsonify({
                    "status": "error",
                    "message": f"Missing required field: {feature}"
                }), 400

        student = pd.DataFrame([{
            "study_hours": data["study_hours"],
            "attendance": data["attendance"],
            "sleep_hours": data["sleep_hours"],
            "internet_usage": data["internet_usage"],
            "assignments_completed": data["assignments_completed"],
            "previous_score": data["previous_score"]
        }])

        risk_prediction = risk_model.predict(
            student
        )[0]

        return jsonify({
            "status": "success",
            "predicted_risk": str(
                risk_prediction
            )
        })

    except Exception as e:

        return jsonify({
            "status": "error",
            "message": str(e)
        }), 500


# ============================================================
# Student Clustering
# ============================================================

@app.route("/predict/cluster", methods=["POST"])
def predict_cluster():

    try:

        data = request.get_json()

        required_features = [
            "study_hours",
            "attendance",
            "sleep_hours",
            "internet_usage",
            "assignments_completed",
            "previous_score"
        ]

        for feature in required_features:

            if feature not in data:

                return jsonify({
                    "status": "error",
                    "message": f"Missing required field: {feature}"
                }), 400

        student = pd.DataFrame([{
            "study_hours": data["study_hours"],
            "attendance": data["attendance"],
            "sleep_hours": data["sleep_hours"],
            "internet_usage": data["internet_usage"],
            "assignments_completed": data["assignments_completed"],
            "previous_score": data["previous_score"]
        }])

        # Scale using K-Means scaler
        student_scaled = cluster_scaler.transform(
            student
        )

        # Predict cluster
        cluster = cluster_model.predict(
            student_scaled
        )[0]

        # Interpret cluster
        if int(cluster) == 0:

            cluster_label = (
                "Lower Academic Performance Learner"
            )

        else:

            cluster_label = (
                "Higher Academic Performance Learner"
            )

        return jsonify({
            "status": "success",
            "cluster": int(cluster),
            "cluster_label": cluster_label
        })

    except Exception as e:

        return jsonify({
            "status": "error",
            "message": str(e)
        }), 500


# ============================================================
# Personalized Recommendations
# ============================================================

@app.route("/recommendations", methods=["POST"])
def recommendations():

    try:

        data = request.get_json()

        required_features = [
            "study_hours",
            "attendance",
            "sleep_hours",
            "internet_usage",
            "assignments_completed",
            "previous_score"
        ]

        for feature in required_features:

            if feature not in data:

                return jsonify({
                    "status": "error",
                    "message": f"Missing required field: {feature}"
                }), 400

        # ----------------------------------------------------
        # Create student DataFrame
        # ----------------------------------------------------

        student = pd.DataFrame([{
            "study_hours": data["study_hours"],
            "attendance": data["attendance"],
            "sleep_hours": data["sleep_hours"],
            "internet_usage": data["internet_usage"],
            "assignments_completed": data["assignments_completed"],
            "previous_score": data["previous_score"]
        }])


        # ----------------------------------------------------
        # Performance prediction
        # ----------------------------------------------------

        student_scaled = performance_scaler.transform(
            student
        )

        predicted_score = performance_model.predict(
            student_scaled
        )[0]

        predicted_score = max(
            0,
            min(100, predicted_score)
        )


        # ----------------------------------------------------
        # Risk prediction
        # ----------------------------------------------------

        risk_prediction = risk_model.predict(
            student
        )[0]


        # ----------------------------------------------------
        # Cluster prediction
        # ----------------------------------------------------

        cluster_scaled = cluster_scaler.transform(
            student
        )

        cluster = cluster_model.predict(
            cluster_scaled
        )[0]


        # ----------------------------------------------------
        # Generate recommendations
        # ----------------------------------------------------

        recommendation_result = generate_recommendations(

            study_hours=data["study_hours"],

            attendance=data["attendance"],

            sleep_hours=data["sleep_hours"],

            internet_usage=data["internet_usage"],

            assignments_completed=data[
                "assignments_completed"
            ],

            previous_score=data["previous_score"],

            predicted_score=float(predicted_score),

            risk_level=str(risk_prediction),

            cluster=int(cluster)
        )


        # ----------------------------------------------------
        # Cluster label
        # ----------------------------------------------------

        if int(cluster) == 0:

            cluster_label = (
                "Lower Academic Performance Learner"
            )

        else:

            cluster_label = (
                "Higher Academic Performance Learner"
            )


        # ----------------------------------------------------
        # Return complete AI result
        # ----------------------------------------------------

        return jsonify({

            "status": "success",

            "predicted_exam_score": round(
                float(predicted_score),
                2
            ),

            "academic_risk": str(
                risk_prediction
            ),

            "cluster": int(cluster),

            "cluster_label": cluster_label,

            "priority":
                recommendation_result[
                    "priority"
                ],

            "recommendations":
                recommendation_result[
                    "recommendations"
                ]
        })


    except Exception as e:

        return jsonify({

            "status": "error",

            "message": str(e)

        }), 500


# ============================================================
# Run Flask API
# ============================================================

if __name__ == "__main__":

    app.run(
        host="127.0.0.1",
        port=5000,
        debug=True
    )