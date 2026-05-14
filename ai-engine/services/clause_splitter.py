import re

def split_clauses(text):

    clauses = re.split(
        r'\n(?=Pasal\s+\d+|[A-Z]\.|[0-9]+\.|\d+\))',
        text
    )

    return [
        c.strip()
        for c in clauses
        if len(c.strip()) > 50
    ]