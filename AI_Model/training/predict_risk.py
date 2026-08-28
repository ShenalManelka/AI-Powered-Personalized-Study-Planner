import joblib
import pandas as pd


# --------------------------------------------------
# 1. Load trained risk model
# --------------------------------------------------

MODEL_PATH = "../models/risk_model.pkl"

model = joblib.load(MODEL_PATH)

print("Risk model loaded successfully.")


# --------------------------------------------------
# 2. Example student
# --------------------------------------------------

student = pd.DataFrame([{
    "study_hours": 8,
    "attendance": 60,
    "sleep_hours": 5,
    "internet_usage": 8,
    "assignments_completed": 3,
    "previous_score": 50
}])


# --------------------------------------------------
# 3. Predict risk
# --------------------------------------------------

prediction = model.predict(student)[0]


# --------------------------------------------------
# 4. Display result
# --------------------------------------------------

print("\nStudent Information")
print("-----------------------------")
print(student.to_string(index=False))

print("\nPredicted Academic Risk")
print("-----------------------------")
print(prediction)
