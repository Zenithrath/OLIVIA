import os

from dotenv import load_dotenv
from langchain_ollama import OllamaEmbeddings

load_dotenv()

def get_embeddings():

    return OllamaEmbeddings(
        model=os.getenv("EMBED_MODEL")
    )