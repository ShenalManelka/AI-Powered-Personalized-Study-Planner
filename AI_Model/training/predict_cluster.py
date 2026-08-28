import joblib
import pandas as pd


# ============================================================
# 1. Load trained models
# ============================================================

MODEL_PATH = "../models/cluster_model.pkl"
SCALER_PATH = "../models/cluster_scaler.pkl"

cluster_model = joblib.load(MODEL_PATH)
scaler = joblib.load(SCALER_PATH)

print("Cluster model loaded successfully.")
print("Cluster scaler loaded successfully.")


# ============================================================
# 2. Example student
# ============================================================

student = pd.DataFrame([{
    "study_hours": 8,
    "attendance": 60,
    "sleep_hours": 5,
    "internet_usage": 8,
    "assignments_completed": 3,
    "previous_score": 50
}])


# ============================================================
# 3. Scale student data
# ============================================================

student_scaled = scaler.transform(student)


# ============================================================
# 4. Predict cluster
# ============================================================

cluster = cluster_model.predict(student_scaled)[0]


# ============================================================
# 5. Interpret Cluster
# ============================================================

cluster_labels = {
    0: "Lower Academic Performance Learner",
    1: "Higher Academic Performance Learner"
}

learning_pattern = cluster_labels.get(
    cluster,
    "Unknown Learning Pattern"
)


# ============================================================
# 6. Display result
# ============================================================

print("\nStudent Information")
print("-----------------------------")
print(student.to_string(index=False))

print("\nPredicted Student Cluster")
print("-----------------------------")
print(f"Cluster: {cluster}")
print(f"Learning Pattern: {learning_pattern}")