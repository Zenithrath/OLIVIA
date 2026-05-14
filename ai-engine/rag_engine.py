import os

from dotenv import load_dotenv

from langchain_chroma import Chroma
from langchain_ollama import ChatOllama
from langchain_core.prompts import PromptTemplate
from langchain_classic.chains import RetrievalQA

from services.embedding_service import get_embeddings

load_dotenv()

CHROMA_PATH = os.getenv("CHROMA_PATH")

PROMPT_TEMPLATE = """
Kamu adalah AI ahli hukum kontrak Indonesia.

Gunakan referensi hukum berikut:
{context}

Analisis klausul berikut:
{question}

Jawab format berikut:

1. STATUS:
2. PENJELASAN:
3. DASAR HUKUM:
4. PIHAK DIRUGIKAN:
5. SARAN:

Gunakan Bahasa Indonesia yang jelas.
"""

def load_vectorstore():

    embeddings = get_embeddings()

    vectorstore = Chroma(
        persist_directory=CHROMA_PATH,
        embedding_function=embeddings,
        collection_name="hukum_indonesia"
    )

    total = vectorstore._collection.count()

    print(f"📚 ChromaDB loaded: {total} chunks")

    return vectorstore

def create_rag_chain():

    vectorstore = load_vectorstore()

    retriever = vectorstore.as_retriever(
        search_type="similarity",
        search_kwargs={"k": 5}
    )

    llm = ChatOllama(
        model=os.getenv("OLLAMA_MODEL"),
        temperature=0.1
    )

    prompt = PromptTemplate(
        template=PROMPT_TEMPLATE,
        input_variables=["context", "question"]
    )

    chain = RetrievalQA.from_chain_type(
        llm=llm,
        retriever=retriever,
        chain_type="stuff",
        chain_type_kwargs={"prompt": prompt},
        return_source_documents=True
    )

    return chain