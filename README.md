# LibertyTrack
A Prisoner Management System with Descriptive Analytics for Moriones Police Station (PS2) (BILANG GO)

# Face Recognition Library

The **`face_recognition`** library is a Python-based tool for facial recognition, detection, and manipulation. This library is built on top of the powerful **dlib** machine learning toolkit and provides easy-to-use APIs for various face-related tasks.

---

## Features
- **Face Detection**: Locate faces in images or video frames.
- **Face Recognition**: Identify or verify faces by comparing them.
- **Facial Landmarks**: Detect key facial features like eyes, nose, and mouth.
- **Face Encoding**: Convert faces into numerical representations for comparison.
- **Face Manipulation**: Transform or adjust facial features.

---

## Installation

### Prerequisites
Before installing the library, ensure you have the following dependencies installed:

1. **Python** (3.6 or higher)
2. **CMake**
   - Linux: `sudo apt install cmake`
   - macOS: `brew install cmake`
   - Windows: Download and install from [CMake.org](https://cmake.org/download/).
3. **Boost Libraries**
   - Linux: `sudo apt install libboost-all-dev`
   - macOS: Automatically handled via pip.
   - Windows: Boost is bundled with dlib installation.

### Installation Steps

1. Install `dlib`:
   ```bash
   pip install dlib
   ```

2. Install `face_recognition`:
   ```bash
   pip install face_recognition
   ```

3. Optional dependencies:
   - **NumPy** (required internally by `face_recognition`):
     ```bash
     pip install numpy
     ```
   - **Pillow** (for image manipulation):
     ```bash
     pip install pillow
     ```

---

## Usage

Here’s a simple example to recognize faces:

```python
import face_recognition

# Load images
known_image = face_recognition.load_image_file("known_face.jpg")
unknown_image = face_recognition.load_image_file("unknown_face.jpg")

# Encode faces
known_encoding = face_recognition.face_encodings(known_image)[0]
unknown_encoding = face_recognition.face_encodings(unknown_image)[0]

# Compare faces
results = face_recognition.compare_faces([known_encoding], unknown_encoding)
print("Is the face a match?", results[0])
```

---

## Common Issues

1. **CMake or dlib installation errors**:
   - Ensure CMake is installed and added to your system’s PATH.
   - On Windows, install **Microsoft Visual C++ Build Tools**.

2. **Boost library issues**:
   - Install Boost libraries for your system.

3. **Permission errors**:
   - Use `sudo` (Linux/macOS) or run as Administrator (Windows).

---

## Applications
- Security systems
- Attendance tracking
- Identity verification
- Facial feature analysis

---

## Contributing

Contributions are welcome! Feel free to open issues or submit pull requests to improve the library or documentation.

---

## License

This project is licensed under the [MIT License](LICENSE).

---

## Acknowledgments
- Built on top of the [dlib](http://dlib.net/) library.
