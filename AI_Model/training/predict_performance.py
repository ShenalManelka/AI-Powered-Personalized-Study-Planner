import joblib
import pandas as pd


# --------------------------------------------------
# 1. Load trained model and scaler
# --------------------------------------------------

MODEL_PATH = "../models/performance_model.pkl"
SCALER_PATH = "../models/performance_scaler.pkl"

model = joblib.load(MODEL_PATH)
scaler = joblib.load(SCALER_PATH)

print("Performance model loaded successfully.")
print("Performance scaler loaded successfully.")


# --------------------------------------------------
# 2. Example student data
# --------------------------------------------------

student = pd.DataFrame([{
    "study_hours": 15,
    "attendance": 85,
    "sleep_hours": 7,
    "internet_usage": 4,
    "assignments_completed": 8,
    "previous_score": 75
}])


# --------------------------------------------------
# 3. Scale student data
# --------------------------------------------------

student_scaled = scaler.transform(student)


# --------------------------------------------------
# 4. Predict exam score
# --------------------------------------------------

prediction = model.predict(student_scaled)[0]


# Keep prediction between 0 and 100
prediction = max(0, min(100, prediction))


# --------------------------------------------------
# 5. Display result
# --------------------------------------------------

print("\nStudent Information")
print("-----------------------------")
print(student.to_string(index=False))

print("\nPredicted Exam Score")
print("-----------------------------")
print(f"{prediction:.2f}")