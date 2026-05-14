from langchain_community.document_loaders import (
    DirectoryLoader,
    TextLoader,
    PyPDFLoader
)

from langchain_text_splitters import (
    RecursiveCharacterTextSplitter
)

from langchain_chroma import Chroma

from services.embedding_service import (
    get_embeddings
)

DOCS_PATH = "./data_hukum"
CHROMA_PATH = "./chroma_db"

def load_documents():

    documents = []

    try:

        pdf_loader = DirectoryLoader(
            DOCS_PATH,
            glob="**/*.pdf",
            loader_cls=PyPDFLoader
        )

        pdf_docs = pdf_loader.load()

        documents.extend(pdf_docs)

        print(f"📄 PDF loaded: {len(pdf_docs)}")

    except Exception as e:

        print(f"❌ Tidak ada PDF: {e}")

    try:

        txt_loader = DirectoryLoader(
            DOCS_PATH,
            glob="**/*.txt",
            loader_cls=TextLoader,
            loader_kwargs={
                "encoding": "utf-8"
            }
        )

        txt_docs = txt_loader.load()

        documents.extend(txt_docs)

        print(f"📄 TXT loaded: {len(txt_docs)}")

    except Exception as e:

        print(f"❌ Tidak ada TXT: {e}")

    print(f"📚 Total dokumen: {len(documents)}")

    return documents

def split_documents(documents):

    splitter = RecursiveCharacterTextSplitter(
        chunk_size=800,
        chunk_overlap=100
    )

    chunks = splitter.split_documents(
        documents
    )

    print(f"🧩 Total chunks: {len(chunks)}")

    return chunks

def index_to_chroma(chunks):

    embeddings = get_embeddings()

    vectorstore = Chroma.from_documents(
        documents=chunks,
        embedding=embeddings,
        persist_directory=CHROMA_PATH,
        collection_name="hukum_indonesia"
    )

    total = vectorstore._collection.count()

    print(f"✅ Berhasil simpan {total} chunks")

if __name__ == "__main__":

    print("=== MULAI INGESTION ===")

    docs = load_documents()

    if len(docs) == 0:

        print("❌ Folder data_hukum kosong")
        exit()

    chunks = split_documents(docs)

    index_to_chroma(chunks)

    print("=== INGESTION SELESAI ===")