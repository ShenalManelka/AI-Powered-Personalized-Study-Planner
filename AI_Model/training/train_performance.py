import os
import pandas as pd
import joblib

from sklearn.model_selection import train_test_split
from sklearn.preprocessing import StandardScaler
from sklearn.neural_network import MLPRegressor
from sklearn.metrics import mean_absolute_error, mean_squared_error, r2_score


# ============================================================
# 1. Load Dataset
# ============================================================

DATA_PATH = "../dataset/student_dataset.csv"
MODEL_PATH = "../models/performance_model.pkl"
SCALER_PATH = "../models/performance_scaler.pkl"

data = pd.read_csv(DATA_PATH)

print("Dataset loaded successfully.")
print("Dataset shape:", data.shape)


# ============================================================
# 2. Define Features and Target
# ============================================================

features = [
    "study_hours",
    "attendance",
    "sleep_hours",
    "internet_usage",
    "assignments_completed",
    "previous_score"
]

target = "exam_score"

X = data[features]
y = data[target]

print("\nFeatures:")
print(features)

print("\nTarget:")
print(target)


# ============================================================
# 3. Split Dataset
# ============================================================

X_train, X_test, y_train, y_test = train_test_split(
    X,
    y,
    test_size=0.20,
    random_state=42
)

print("\nTraining samples:", len(X_train))
print("Testing samples:", len(X_test))


# ============================================================
# 4. Scale Features
# ============================================================

scaler = StandardScaler()

X_train_scaled = scaler.fit_transform(X_train)
X_test_scaled = scaler.transform(X_test)

print("\nFeatures scaled successfully.")


# ============================================================
# 5. Train Neural Network
# ============================================================

model = MLPRegressor(
    hidden_layer_sizes=(64, 32),
    activation="relu",
    solver="adam",
    learning_rate_init=0.001,
    max_iter=1000,
    early_stopping=True,
    random_state=42
)

print("\nTraining Neural Network...")

model.fit(
    X_train_scaled,
    y_train
)

print("Neural Network trained successfully.")


# ============================================================
# 6. Make Predictions
# ============================================================

predictions = model.predict(X_test_scaled)

# Keep predictions between 0 and 100
predictions = predictions.clip(0, 100)


# ============================================================
# 7. Evaluate Model
# ============================================================

mae = mean_absolute_error(
    y_test,
    predictions
)

rmse = mean_squared_error(
    y_test,
    predictions
) ** 0.5

r2 = r2_score(
    y_test,
    predictions
)


# ============================================================
# 8. Display Performance
# ============================================================

print("\n")
print("=" * 70)
print("FINAL NEURAL NETWORK PERFORMANCE")
print("=" * 70)

print(f"MAE  : {mae:.4f}")
print(f"RMSE : {rmse:.4f}")
print(f"R²   : {r2:.4f}")


# ============================================================
# 9. Save Model and Scaler
# ============================================================

os.makedirs("../models", exist_ok=True)

joblib.dump(
    model,
    MODEL_PATH
)

joblib.dump(
    scaler,
    SCALER_PATH
)


# ============================================================
# 10. Confirmation
# ============================================================

print("\n")
print("=" * 70)
print("MODEL SAVED SUCCESSFULLY")
print("=" * 70)

print("Final model: Neural Network (MLPRegressor)")
print("Model path:", MODEL_PATH)
print("Scaler path:", SCALER_PATH)