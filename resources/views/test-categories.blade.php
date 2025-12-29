<!DOCTYPE html>
<html>
<head>
    <title>Test Catégories</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .card { border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 5px; }
        img { max-width: 200px; height: auto; margin: 10px 0; }
        .error { color: red; font-weight: bold; }
        .success { color: green; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Test Catégories</h1>
    
    @if($categories->count() > 0)
        <p class="success">✅ {{ $categories->count() }} catégories trouvées!</p>
        
        @foreach($categories as $cat)
            <div class="card">
                <h3>{{ $cat->nom }}</h3>
                <p><strong>Description:</strong> {{ $cat->description }}</p>
                <p><strong>Image URL:</strong> {{ $cat->image }}</p>
                
                @if($cat->image)
                    <p><strong>Aperçu:</strong></p>
                    <img src="{{ $cat->image }}" alt="{{ $cat->nom }}" onerror="this.style.border='3px solid red'; this.alt='ERREUR: Image non chargée'">
                @else
                    <p class="error">❌ Pas d'image définie</p>
                @endif
                
                <p><strong>Actif:</strong> {{ $cat->est_active ? 'Oui' : 'Non' }}</p>
                <p><strong>Ordre:</strong> {{ $cat->ordre_affichage }}</p>
            </div>
        @endforeach
    @else
        <p class="error">❌ Aucune catégorie trouvée!</p>
    @endif
    
    <hr>
    <p><a href="/">← Retour à l'accueil</a></p>
</body>
</html>
