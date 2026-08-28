# AI-Powered Personalized Study Planner and Intelligent Academic Scheduling System

An AI-powered web-based academic planning system designed to support undergraduate IT students in managing academic activities, understanding predicted academic performance, identifying academic risks, receiving personalised recommendations, and creating flexible study plans.

---

## Project Overview

Undergraduate students are required to manage multiple academic responsibilities, including subjects, assignments, examinations, deadlines, independent study, and other academic activities. Traditional study-planning systems mainly focus on recording tasks and displaying schedules without considering individual academic characteristics and learning behaviour.

This project addresses this limitation by combining academic management and study-planning functionality with Artificial Intelligence (AI) and Machine Learning (ML).

The system analyses student-related academic and study information to provide:

- Academic performance prediction
- Academic risk classification
- Student behaviour clustering
- Personalised academic recommendations
- Adaptive study planning
- Academic alerts
- AI-assisted learning support

The system is designed specifically for undergraduate IT students and allows students to retain control over generated study plans through manual adjustments.

---

## Main Objectives

### Main Objective

To develop an AI-powered personalised study planning and academic scheduling system that applies Machine Learning techniques to analyse student data, predict academic outcomes, identify academic risks, and provide personalised study recommendations for undergraduate IT students.

### Specific Objectives

The project aims to:

1. Collect and prepare relevant academic and study-related data for Machine Learning analysis.
2. Analyse student-related data to identify patterns associated with academic performance.
3. Develop a Machine Learning model for academic performance prediction.
4. Develop a classification model for identifying academic risk levels.
5. Apply clustering techniques to identify students with similar academic characteristics.
6. Integrate Machine Learning models with the web application through APIs.
7. Provide personalised recommendations to support academic prioritisation.
8. Develop flexible scheduling functionality for organising and adjusting study activities.
9. Evaluate the Machine Learning models and overall system for effectiveness and reliability.

---

## Key Features

### Student Management

- Student registration and authentication
- Secure login and logout
- Student profile management
- Academic information management

### Academic Management

- Subject management
- Assignment management
- Examination management
- Upcoming examination tracking
- Completed examination records
- Examination score recording
- Academic progress monitoring

### Study Planning

- Study availability management
- Personalised study planning
- Calendar-based study scheduling
- Study session management
- Study progress tracking
- Manual schedule adjustments
- Academic task prioritisation

### AI and Machine Learning

- Academic performance prediction
- Per-exam prediction for upcoming examinations
- Academic risk classification
- Student behaviour clustering
- Learner profile identification
- Personalised recommendations
- AI-generated academic action plans

### Smart Alerts

- Upcoming examination alerts
- Assignment deadline alerts
- Study-related notifications
- Academic progress alerts

### AI Learning Assistant

The system includes an optional Gemini-based AI Learning Assistant that can provide:

- Question answering
- Topic explanations
- Learning assistance
- Quiz generation
- Academic guidance

---

## AI and Machine Learning Components

The project contains several AI and Machine Learning components that perform different analytical tasks.

### 1. Academic Performance Prediction

The performance prediction component estimates a student's expected academic performance using relevant academic and study-related information.

The model can use features such as:

- Study hours
- Attendance
- Sleep hours
- Internet usage
- Assignment completion
- Previous academic performance

For upcoming examinations, relevant subject-specific academic information can be used to produce individual prediction results.

### 2. Academic Risk Prediction

The academic risk component classifies students according to their potential level of academic risk.

The system supports risk categories such as:

- Low Risk
- Medium Risk
- High Risk

Risk predictions are intended as decision-support information rather than guaranteed academic outcomes.

### 3. Student Behaviour Clustering

K-Means clustering is used as an unsupervised learning technique to identify groups of students with similar academic and behavioural characteristics.

The resulting learner profiles can provide additional information for personalised recommendations and study planning.

### 4. Personalised Recommendations

The recommendation component uses available student information and AI analysis results to generate academic suggestions.

Recommendations can consider:

- Academic performance
- Academic risk
- Study habits
- Assignment progress
- Examination deadlines
- Available study time
- Learner characteristics

### 5. Adaptive Study Planning

The study planner combines academic information with AI-supported insights to help students organise study activities.

The scheduling process can consider:

- Upcoming examinations
- Assignment deadlines
- Academic priorities
- Available study periods
- Study progress
- Predicted performance
- Academic risk

Students can review and manually modify generated study plans.

---

## Per-Exam AI Prediction

A key feature of the system is the ability to generate predictions for individual upcoming examinations instead of relying only on one global prediction.

For each upcoming examination, the system can use two types of information.

### Global Student Information

- Study hours
- Attendance
- Sleep hours
- Internet usage

### Subject-Specific Information

- Completed assignments for the relevant subject
- Previous examination performance for the relevant subject

This allows the system to generate separate prediction results for different examinations.

Example:

```text
Machine Learning Exam
        |
        v
Subject-specific academic information
        |
        v
Machine Learning Model
        |
        v
Predicted Score + Academic Risk


Software Engineering Exam
        |
        v
Subject-specific academic information
        |
        v
Machine Learning Model
        |
        v
Predicted Score + Academic Risk
```

## AI Prediction Workflow

The general AI analysis workflow is:

```text
Student Academic Information
            |
            v
    Laravel Application
            |
            v
    Academic Data Processing
            |
            v
       Flask REST API
            |
            v
      Python ML Models
            |
       +----+----+
       |    |    |
       v    v    v
 Performance Risk Cluster
 Prediction Prediction Analysis
       |    |    |
       +----+----+
            |
            v
    Laravel Application
            |
            v
 Personalised Recommendations
            |
            v
       Study Planner
            |
            v
      Student Dashboard
```

## Overall System Architecture

The system uses a multi-component architecture that separates the main web application, database, Machine Learning services, and external AI functionality.

```text
                         +------------------+
                         |     Student      |
                         +--------+---------+
                                  |
                                  v
                    +--------------------------+
                    |   Laravel Web Application |
                    |       PHP / Blade        |
                    +------------+-------------+
                                 |
                  +--------------+--------------+
                  |              |              |
                  v              v              v
          +-------------+  +-----------+  +-------------+
          |    MySQL    |  | Flask API |  |  Gemini API |
          |   Database  |  |           |  |             |
          +-------------+  +-----+-----+  +-------------+
                                  |
                                  v
                       +----------------------+
                       |   Python ML Models   |
                       +----------------------+
                       | Performance Model    |
                       | Risk Model           |
                       | Clustering Model     |
                       | Recommendation       |
                       +----------------------+
```

## Technology Stack

### Backend
- Laravel
- PHP
- MySQL

### Frontend
- Blade Templates
- Bootstrap
- HTML
- CSS
- JavaScript

### Machine Learning
- Python
- Pandas
- NumPy
- Scikit-learn
- Flask
- Joblib
- Machine Learning models

### Data Processing and Analysis
- Pandas
- NumPy
- Scikit-learn
- Jupyter Notebook

### Data Visualisation and Scheduling
- Chart.js
- FullCalendar.js

### Generative AI
- Gemini API

## Repository Structure

The repository contains two main components:

```text
AI-Powered-Personalized-Study-Planner/
|
+-- AI_Model/
|   |
|   +-- app.py
|   +-- requirements.txt
|   +-- training scripts
|   +-- trained model files
|   +-- scaler files
|   +-- dataset files
|   +-- ...
|
+-- ai-study-planner/
|   |
|   +-- app/
|   +-- bootstrap/
|   +-- config/
|   +-- database/
|   +-- public/
|   +-- resources/
|   +-- routes/
|   +-- storage/
|   +-- tests/
|   +-- artisan
|   +-- composer.json
|   +-- package.json
|   +-- ...
|
+-- .gitignore
|
+-- README.md
```

## Laravel Application

The `ai-study-planner` directory contains the main web application.

It is responsible for:

- User authentication
- Student profile management
- Subject management
- Assignment management
- Examination management
- Study availability
- Study planning
- Academic progress
- AI prediction requests
- API communication
- Recommendations
- Smart alerts
- AI Learning Assistant
- Dashboard functionality

## AI Model Application

The `AI_Model` directory contains the Python Machine Learning implementation.

It is responsible for:

- Data preparation
- Data processing
- Machine Learning model training
- Model evaluation
- Model storage
- Prediction services
- Academic risk classification
- Student clustering
- Recommendation processing
- Flask REST API communication

The separation of the AI component from the Laravel application allows Machine Learning models to be developed, trained, evaluated, and updated independently.

## Flask REST API

Flask acts as the communication layer between the Laravel application and the Python Machine Learning models.

The general communication process is:

```text
Laravel Application
        |
        | HTTP Request
        v
    Flask API
        |
        v
   Python ML Model
        |
        v
 Prediction Result
        |
        | JSON Response
        v
Laravel Application
```

The Flask API is responsible for:

- Receiving prediction requests
- Validating input data
- Preparing model inputs
- Loading trained models
- Generating predictions
- Returning results to Laravel
- Handling API errors

## Machine Learning Model Evaluation

### Performance Prediction

The academic performance prediction task is treated as a regression problem because the target is a continuous academic score.

Common evaluation metrics include:

- **MAE**: Mean Absolute Error measures the average absolute difference between actual and predicted values.
- **RMSE**: Root Mean Squared Error measures prediction error while giving greater importance to larger errors.
- **R²**: R² indicates the proportion of variation in the target variable explained by the model.

### Risk Classification

The academic risk model is evaluated as a classification problem.

Possible evaluation measures include:

- Accuracy
- Precision
- Recall
- F1-score
- Confusion Matrix

### Clustering

K-Means clustering is used to identify groups of students with similar characteristics.

The general process is:

```text
Student Data
     |
     v
Data Preprocessing
     |
     v
Feature Selection
     |
     v
Feature Scaling
     |
     v
K-Means Clustering
     |
     v
Learner Groups
     |
     v
Learner Profiles
     |
     v
Recommendations
```

## Academic Planning Workflow

```text
Student Information
        |
        v
Subjects + Assignments + Exams
        |
        v
Study Availability
        |
        v
AI Analysis
        |
        +----> Performance Prediction
        |
        +----> Academic Risk
        |
        +----> Learner Profile
        |
        v
Personalised Recommendations
        |
        v
Study Plan
        |
        v
Student Review
        |
        v
Manual Adjustment
        |
        v
Updated Study Plan
```

## Main Application Modules

### Authentication

Provides:
- Registration
- Login
- Logout
- Authentication
- Access control

### Student Profile

Students can manage relevant academic and study information such as:
- Study hours
- Attendance
- Sleep hours
- Internet usage
- Academic information

### Subject Management

Students can create and manage academic subjects used throughout the planning and prediction processes.

### Assignment Management

Students can:
- Add assignments
- Edit assignments
- Delete assignments
- Track assignment status
- Monitor assignment progress
- Manage deadlines

### Examination Management

The examination module supports:
- Upcoming examinations
- Completed examinations
- Examination dates
- Subject association
- Examination scores
- Examination tracking

### Study Availability

Students can define periods during which they are available for studying.
The scheduler can use this information when generating study activities.

### Study Planner

The study planner provides:
- Study activities
- Scheduled study sessions
- Academic priorities
- Calendar-based planning
- Progress tracking
- Manual adjustments

### AI Predictions

The AI prediction dashboard provides:
- Predicted examination scores
- Academic risk levels
- Learner profile information
- AI-generated action plans

### Recommendations

The recommendation component provides academic suggestions based on student information and AI analysis.

### Smart Alerts

The system can provide alerts related to:
- Upcoming examinations
- Assignment deadlines
- Academic progress
- Study activities

### AI Learning Assistant

The optional AI Learning Assistant uses the Gemini API to provide additional educational support.

Students can use the assistant to:
- Ask academic questions
- Request explanations
- Generate quizzes
- Explore difficult topics
- Receive learning assistance

The Gemini assistant is supplementary to the core Machine Learning components. Academic performance prediction, risk classification, and learner clustering are handled by the dedicated ML services.

## Student Control and Human-Centred Design

The system is designed to support students rather than replace their decision-making.
AI predictions and recommendations are presented as decision-support information.

Students can:
- Review predictions
- Review recommendations
- Modify study plans
- Change study activities
- Adjust priorities
- Update academic information
- Change scheduled sessions

This allows the system to provide personalised support while maintaining student autonomy.
