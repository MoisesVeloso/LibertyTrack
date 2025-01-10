import sys
import face_recognition
import os

def verify_face(image_data_path, input_image_path):
    known_face_encodings = []
    
    for filename in os.listdir(image_data_path):
        if filename.endswith(('.jpg', '.png', '.jpeg')): 
            image_path = os.path.join(image_data_path, filename)
            image = face_recognition.load_image_file(image_path)
            encoding = face_recognition.face_encodings(image)
            if encoding: 
                known_face_encodings.append(encoding[0])

    print(f"Loaded {len(known_face_encodings)} known face encodings.") 

    input_image = face_recognition.load_image_file(input_image_path)
    input_encoding = face_recognition.face_encodings(input_image)

    if not input_encoding:
        print("No face detected in the input image.")
        return False

    matches = face_recognition.compare_faces(known_face_encodings, input_encoding[0])
    print(f"Matches found: {matches}")  

    if any(matches):
        return True
    else:
        return False

if __name__ == "__main__":
    if len(sys.argv) < 3:
        print("Usage: python facial_recognition_script.py <image_data_path> <input_image_path>")
        sys.exit(1)

    image_data_path = sys.argv[1]
    input_image_path = sys.argv[2]

    result = verify_face(image_data_path, input_image_path)
    print("Match Found" if result else "No Match")
