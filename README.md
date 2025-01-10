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

## Installation

1. Clone the LibertyTrack repository:
   ```bash
   git clone <repository-url>
   cd LibertyTrack
   ```

2. Install the required Python libraries:
   ```bash
   pip install -r requirements.txt
   ```

3. Run the application:
   ```bash
   python app.py
   ```

---

## Usage
LibertyTrack ensures that only verified changes are applied to inmate data by using facial recognition technology. For detailed usage instructions, refer to the [User Guide](USER_GUIDE.md).

---

## License
This project is licensed under the [MIT License](LICENSE).

