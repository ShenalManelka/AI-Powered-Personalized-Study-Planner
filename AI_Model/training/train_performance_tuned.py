import os
import pandas as pd
import joblib

from sklearn.model_selection import train_test_split, RandomizedSearchCV
from sklearn.linear_model import LinearRegression
from sklearn.ensemble import RandomForestRegressor
from sklearn.metrics import mean_absolute_error, mean_squared_error, r2_score
from xgboost import XGBRegressor


# ============================================================
# 1. Load Dataset
# ============================================================

DATA_PATH = "../dataset/student_dataset.csv"
MODEL_PATH = "../models/performance_model.pkl"

data = pd.read_csv(DATA_PATH)

print("Dataset loaded successfully.")
print("Dataset shape:", data.shape)


# ============================================================
# 2. Features and Target
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


# ============================================================
# 3. Train/Test Split
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
# 4. Baseline - Linear Regression
# ============================================================

linear_model = LinearRegression()

linear_model.fit(X_train, y_train)

linear_predictions = linear_model.predict(X_test)
linear_predictions = linear_predictions.clip(0, 100)

linear_mae = mean_absolute_error(y_test, linear_predictions)
linear_rmse = mean_squared_error(y_test, linear_predictions) ** 0.5
linear_r2 = r2_score(y_test, linear_predictions)


# ============================================================
# 5. Tune Random Forest
# ============================================================

print("\n" + "=" * 70)
print("TUNING RANDOM FOREST")
print("=" * 70)

rf = RandomForestRegressor(
    random_state=42
)

rf_parameters = {
    "n_estimators": [200, 300, 500],
    "max_depth": [None, 5, 10, 15, 20],
    "min_samples_split": [2, 5, 10],
    "min_samples_leaf": [1, 2, 4],
    "max_features": [1.0, "sqrt", "log2"]
}

rf_search = RandomizedSearchCV(
    estimator=rf,
    param_distributions=rf_parameters,
    n_iter=15,
    scoring="neg_root_mean_squared_error",
    cv=3,
    random_state=42,
    n_jobs=-1
)

rf_search.fit(X_train, y_train)

best_rf = rf_search.best_estimator_

rf_predictions = best_rf.predict(X_test)
rf_predictions = rf_predictions.clip(0, 100)

rf_mae = mean_absolute_error(y_test, rf_predictions)
rf_rmse = mean_squared_error(y_test, rf_predictions) ** 0.5
rf_r2 = r2_score(y_test, rf_predictions)

print("\nBest Random Forest parameters:")
print(rf_search.best_params_)

print(f"\nRandom Forest MAE  : {rf_mae:.4f}")
print(f"Random Forest RMSE : {rf_rmse:.4f}")
print(f"Random Forest R²   : {rf_r2:.4f}")


# ============================================================
# 6. Tune XGBoost
# ============================================================

print("\n" + "=" * 70)
print("TUNING XGBOOST")
print("=" * 70)

xgb = XGBRegressor(
    objective="reg:squarederror",
    random_state=42
)

xgb_parameters = {
    "n_estimators": [100, 200, 300, 500],
    "learning_rate": [0.01, 0.03, 0.05, 0.1],
    "max_depth": [2, 3, 4, 5, 6],
    "min_child_weight": [1, 3, 5],
    "subsample": [0.7, 0.8, 0.9, 1.0],
    "colsample_bytree": [0.7, 0.8, 0.9, 1.0]
}

xgb_search = RandomizedSearchCV(
    estimator=xgb,
    param_distributions=xgb_parameters,
    n_iter=20,
    scoring="neg_root_mean_squared_error",
    cv=3,
    random_state=42,
    n_jobs=-1
)

xgb_search.fit(X_train, y_train)

best_xgb = xgb_search.best_estimator_

xgb_predictions = best_xgb.predict(X_test)
xgb_predictions = xgb_predictions.clip(0, 100)

xgb_mae = mean_absolute_error(y_test, xgb_predictions)
xgb_rmse = mean_squared_error(y_test, xgb_predictions) ** 0.5
xgb_r2 = r2_score(y_test, xgb_predictions)

print("\nBest XGBoost parameters:")
print(xgb_search.best_params_)

print(f"\nXGBoost MAE  : {xgb_mae:.4f}")
print(f"XGBoost RMSE : {xgb_rmse:.4f}")
print(f"XGBoost R²   : {xgb_r2:.4f}")


# ============================================================
# 7. Final Comparison
# ============================================================

results = pd.DataFrame({
    "Model": [
        "Linear Regression",
        "Random Forest",
        "XGBoost"
    ],
    "MAE": [
        linear_mae,
        rf_mae,
        xgb_mae
    ],
    "RMSE": [
        linear_rmse,
        rf_rmse,
        xgb_rmse
    ],
    "R2": [
        linear_r2,
        rf_r2,
        xgb_r2
    ]
})


print("\n" + "=" * 70)
print("FINAL MODEL COMPARISON")
print("=" * 70)

print(
    results.to_string(
        index=False,
        formatters={
            "MAE": "{:.4f}".format,
            "RMSE": "{:.4f}".format,
            "R2": "{:.4f}".format
        }
    )
)


# ============================================================
# 8. Select Best Model
# ============================================================

best_index = results["RMSE"].idxmin()

best_model_name = results.loc[best_index, "Model"]

if best_model_name == "Linear Regression":
    final_model = linear_model

elif best_model_name == "Random Forest":
    final_model = best_rf

else:
    final_model = best_xgb


print("\n" + "=" * 70)
print("SELECTED FINAL MODEL")
print("=" * 70)

print("Model:", best_model_name)


# ============================================================
# 9. Save Final Model
# ============================================================

os.makedirs("../models", exist_ok=True)

joblib.dump(final_model, MODEL_PATH)

print("\nFinal model saved successfully.")
print("Path:", MODEL_PATH)