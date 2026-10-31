from fastapi import FastAPI

app = FastAPI()


@app.get("/")
def root():
    return {
        "service": "FastAPI",
        "message": "Hello from AI service"
    }


@app.get("/health")
def health():
    return {
        "status": "ok"
    }