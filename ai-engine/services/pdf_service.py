from langchain_community.document_loaders import PyPDFLoader
from docx import Document

def extract_pdf(path):

    loader = PyPDFLoader(path)

    pages = loader.load()

    return "\n".join([
        p.page_content for p in pages
    ])

def extract_docx(path):

    doc = Document(path)

    return "\n".join([
        p.text for p in doc.paragraphs
    ])

def extract_txt(path):

    with open(path, "r", encoding="utf-8") as f:
        return f.read()

def load_document(path):

    if path.endswith(".pdf"):
        return extract_pdf(path)

    elif path.endswith(".docx"):
        return extract_docx(path)

    elif path.endswith(".txt"):
        return extract_txt(path)

    else:
        raise ValueError("Format file tidak didukung")