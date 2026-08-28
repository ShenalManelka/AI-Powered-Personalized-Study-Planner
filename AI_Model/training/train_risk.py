import os
import pandas as pd
import joblib

from sklearn.model_selection import train_test_split
from sklearn.preprocessing import StandardScaler
from sklearn.pipeline import Pipeline

from sklearn.linear_model import LogisticRegression
from sklearn.tree import DecisionTreeClassifier
from sklearn.ensemble import RandomForestClassifier

from sklearn.metrics import (
    accuracy_score,
    precision_score,
    recall_score,
    f1_score,
    classification_report
)


# ============================================================
# 1. Load Dataset
# ============================================================

DATA_PATH = "../dataset/student_dataset.csv"

data = pd.read_csv(DATA_PATH)

print("Dataset loaded successfully.")
print("Dataset shape:", data.shape)


# ============================================================
# 2. Create Academic Risk Labels
# ============================================================

def assign_risk(score):

    if score < 50:
        return "High"

    elif score < 70:
        return "Medium"

    else:
        return "Low"


data["risk_level"] = data["exam_score"].apply(assign_risk)


print("\nRisk distribution")
print("============================")
print(data["risk_level"].value_counts())
print("\nRisk percentages")
print("============================")
print(
    (data["risk_level"].value_counts(normalize=True) * 100)
    .round(2)
)


# ============================================================
# 3. Define Features and Target
# ============================================================

features = [
    "study_hours",
    "attendance",
    "sleep_hours",
    "internet_usage",
    "assignments_completed",
    "previous_score"
]

target = "risk_level"

X = data[features]
y = data[target]


# ============================================================
# 4. Split Dataset
# ============================================================

X_train, X_test, y_train, y_test = train_test_split(
    X,
    y,
    test_size=0.20,
    random_state=42,
    stratify=y
)

print("\nTraining samples:", len(X_train))
print("Testing samples:", len(X_test))


# ============================================================
# 5. Define Classification Models
# ============================================================

models = {

    "Logistic Regression": Pipeline([
        ("scaler", StandardScaler()),
        ("model", LogisticRegression(
            max_iter=1000,
            random_state=42
        ))
    ]),

    "Decision Tree": DecisionTreeClassifier(
        random_state=42,
        max_depth=6
    ),

    "Random Forest": RandomForestClassifier(
        n_estimators=200,
        random_state=42,
        n_jobs=-1
    )
}


# ============================================================
# 6. Train and Evaluate Models
# ============================================================

results = []

trained_models = {}

for name, model in models.items():

    print("\nTraining:", name)

    model.fit(X_train, y_train)

    predictions = model.predict(X_test)

    accuracy = accuracy_score(y_test, predictions)

    precision = precision_score(
        y_test,
        predictions,
        average="weighted",
        zero_division=0
    )

    recall = recall_score(
        y_test,
        predictions,
        average="weighted",
        zero_division=0
    )

    f1 = f1_score(
        y_test,
        predictions,
        average="weighted",
        zero_division=0
    )

    results.append({
        "Model": name,
        "Accuracy": accuracy,
        "Precision": precision,
        "Recall": recall,
        "F1": f1
    })

    trained_models[name] = model


# ============================================================
# 7. Display Model Comparison
# ============================================================

results_df = pd.DataFrame(results)

results_df = results_df.sort_values(
    by="F1",
    ascending=False
)

print("\nModel Comparison")
print("============================")
print(
    results_df.to_string(
        index=False,
        float_format=lambda x: f"{x:.4f}"
    )
)


# ============================================================
# 8. Select Best Model
# ============================================================

best_model_name = results_df.iloc[0]["Model"]

best_model = trained_models[best_model_name]

print("\nSelected Model")
print("============================")
print(best_model_name)


# ============================================================
# 9. Detailed Evaluation
# ============================================================

final_predictions = best_model.predict(X_test)

print("\nClassification Report")
print("============================")

print(
    classification_report(
        y_test,
        final_predictions,
        zero_division=0
    )
)


# ============================================================
# 10. Save Final Risk Model
# ============================================================

MODEL_PATH = "../models/risk_model.pkl"

os.makedirs("../models", exist_ok=True)

joblib.dump(best_model, MODEL_PATH)

print("\nRisk model saved successfully.")
print("Model path:", MODEL_PATH)