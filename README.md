# LibertyTrack
A Prisoner Management System with Descriptive Analytics for Moriones Police Station (PS2) (BILANG GO)

## Requirements

### 1. Python (3.6 or higher)
Python is required to run LibertyTrack and its dependencies.

- **Download**: [https://www.python.org/downloads/](https://www.python.org/downloads/)
- **Install Command** (Linux/MacOS):
  ```bash
  sudo apt install python3
  ```

### 2. CMake
CMake is used to build the `dlib` library, a dependency of `face_recognition`.

- **Download**: [https://cmake.org/download/](https://cmake.org/download/)
- **Install Command**:
  - **Linux**:
    ```bash
    sudo apt install cmake
    ```
  - **macOS**:
    ```bash
    brew install cmake
    ```
  - **Windows**:
    Download and install from [https://cmake.org/download/](https://cmake.org/download/).

### 3. Dlib
Dlib is a machine learning library used by `face_recognition` for facial recognition tasks.

- **Install Command**:
  ```bash
  pip install dlib
  ```

---
## Usage
LibertyTrack ensures that only verified changes are applied to inmate data by using facial recognition technology.

---
## Troubleshooting
If you encounter errors during installation or runtime, ensure that your environment variables are correctly set up:

- **Check Python Path**:
  Ensure that Python is added to your system's PATH.
  ```bash
  python --version
  ```
  If this command fails, add Python to your PATH.

- **Check CMake Path**:
  Ensure that CMake is accessible via the terminal/command prompt.
  ```bash
  cmake --version
  ```
  If this command fails, add CMake to your PATH.

- **Inatall Visual C++ Build Tools (Windows)**:
  CMake requires a C++ compiler to build libraries like dlib. For Windows users, you can install the Visual C++ Build Tools:

Download the installer from [Microsoft](https://visualstudio.microsoft.com/visual-cpp-build-tools/).

During installation, ensure you select the C++ Build Tools workload.

- **Check Dependencies**:
  Verify that all required libraries are installed by running:
  ```bash
  pip list
  ```

---
