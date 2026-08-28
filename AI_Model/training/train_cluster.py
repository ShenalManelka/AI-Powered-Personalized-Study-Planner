import os
import pandas as pd
import joblib

from sklearn.preprocessing import StandardScaler
from sklearn.cluster import KMeans
from sklearn.metrics import silhouette_score


# ============================================================
# 1. Load Dataset
# ============================================================

DATA_PATH = "../dataset/student_dataset.csv"

data = pd.read_csv(DATA_PATH)

print("Dataset loaded successfully.")
print("Dataset shape:", data.shape)


# ============================================================
# 2. Select Clustering Features
# ============================================================

features = [
    "study_hours",
    "attendance",
    "sleep_hours",
    "internet_usage",
    "assignments_completed",
    "previous_score"
]

X = data[features]


# ============================================================
# 3. Scale Features
# ============================================================

scaler = StandardScaler()

X_scaled = scaler.fit_transform(X)

print("\nFeatures scaled successfully.")


# ============================================================
# 4. Test Different Numbers of Clusters
# ============================================================

results = []

print("\nCluster Evaluation")
print("============================")

for k in range(2, 7):

    kmeans = KMeans(
        n_clusters=k,
        random_state=42,
        n_init=10
    )

    labels = kmeans.fit_predict(X_scaled)

    silhouette = silhouette_score(
        X_scaled,
        labels
    )

    results.append({
        "Clusters": k,
        "Silhouette Score": silhouette
    })

    print(
        f"k = {k} | "
        f"Silhouette Score = {silhouette:.4f}"
    )


# ============================================================
# 5. Select Best Number of Clusters
# ============================================================

results_df = pd.DataFrame(results)

best_row = results_df.loc[
    results_df["Silhouette Score"].idxmax()
]

best_k = int(best_row["Clusters"])

print("\nSelected Number of Clusters")
print("============================")
print("Best k:", best_k)


# ============================================================
# 6. Train Final K-Means Model
# ============================================================

cluster_model = KMeans(
    n_clusters=best_k,
    random_state=42,
    n_init=10
)

cluster_labels = cluster_model.fit_predict(X_scaled)


# ============================================================
# 7. Add Cluster Labels
# ============================================================

data["cluster"] = cluster_labels


print("\nCluster Distribution")
print("============================")

print(
    data["cluster"].value_counts().sort_index()
)


# ============================================================
# 8. Display Cluster Profiles
# ============================================================

cluster_profile = data.groupby("cluster")[features].mean()

print("\nCluster Profiles")
print("============================")

print(
    cluster_profile.round(2)
)


# ============================================================
# 9. Save Models
# ============================================================

os.makedirs("../models", exist_ok=True)

joblib.dump(
    cluster_model,
    "../models/cluster_model.pkl"
)

joblib.dump(
    scaler,
    "../models/cluster_scaler.pkl"
)


print("\nClustering model saved successfully.")

print("Model:")
print("../models/cluster_model.pkl")

print("\nScaler:")
print("../models/cluster_scaler.pkl")