def analyze_clause(chain, clause):

    response = chain.invoke({
        "query": clause
    })

    return {
        "analysis": response["result"],
        "sources": response.get(
            "source_documents",
            []
        )
    }