import json
import sys

from services.pdf_service import load_document
from services.clause_splitter import split_clauses
from services.retrieval_service import get_chain
from services.analysis_service import analyze_clause

OUTPUT_FILE = "./outputs/hasil_analisis.json"

def analyze_contract(path):

    print(f"\n📄 Membaca file: {path}")

    text = load_document(path)

    clauses = split_clauses(text)

    print(f"📋 Total klausul: {len(clauses)}")

    chain = get_chain()

    results = []

    for i, clause in enumerate(clauses, 1):

        print(f"\n🔍 Analisis klausul {i}")

        result = analyze_clause(
            chain,
            clause
        )

        analysis = result["analysis"]

        print(analysis)

        results.append({
            "klausul_ke": i,
            "teks": clause,
            "analisis": analysis
        })

    with open(
        OUTPUT_FILE,
        "w",
        encoding="utf-8"
    ) as f:

        json.dump(
            results,
            f,
            ensure_ascii=False,
            indent=2
        )

    print(f"\n✅ Hasil tersimpan: {OUTPUT_FILE}")

if __name__ == "__main__":

    if len(sys.argv) < 2:

        print(
            "python analyze.py file.pdf"
        )

    else:

        analyze_contract(sys.argv[1])