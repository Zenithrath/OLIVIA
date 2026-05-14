import os
import json
import shutil

from fastapi import FastAPI
from fastapi import UploadFile
from fastapi import File

from services.pdf_service import load_document
from services.clause_splitter import split_clauses
from services.retrieval_service import get_chain
from services.analysis_service import analyze_clause

app = FastAPI()

UPLOAD_FOLDER = "./uploads"

@app.get("/")
def home():

    return {
        "status": "AI Legal API Running"
    }

@app.post("/analyze")
async def analyze(
    file: UploadFile = File(...)
):

    file_path = os.path.join(
        UPLOAD_FOLDER,
        file.filename
    )

    with open(file_path, "wb") as buffer:

        shutil.copyfileobj(
            file.file,
            buffer
        )

    text = load_document(file_path)

    clauses = split_clauses(text)

    chain = get_chain()

    results = []

    for clause in clauses:

        result = analyze_clause(
            chain,
            clause
        )

        results.append({
            "clause": clause,
            "analysis": result["analysis"]
        })

    with open(
        "./outputs/hasil_analisis.json",
        "w",
        encoding="utf-8"
    ) as f:

        json.dump(
            results,
            f,
            ensure_ascii=False,
            indent=2
        )

    return {
        "status": "success",
        "total_clause": len(results),
        "results": results
    }