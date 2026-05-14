# cek_db.py - Cek isi ChromaDB

import chromadb

client = chromadb.PersistentClient(path="./chroma_db")

collections = client.list_collections()
if not collections:
    print("❌ ChromaDB kosong! Jalankan ingest.py dulu.")
else:
    for col in collections:
        print(f"✅ Collection: {col.name}")
        print(f"   Jumlah chunks: {col.count()}")

        # Tampilkan 2 sample data
        sample = col.get(limit=2)
        print("   Sample data:")
        for doc in sample["documents"]:
            print(f"   → {doc[:100]}...")