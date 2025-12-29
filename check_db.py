import mysql.connector

conn = mysql.connector.connect(
    host='127.0.0.1',
    user='root',
    password='',
    database='restaurant_gestion'
)
cursor = conn.cursor()

# Récupérer tous les noms de tables
cursor.execute('SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = "restaurant_gestion"')
tables = [row[0] for row in cursor.fetchall()]

print("=== État de la base de données ===")
total_rows = 0
for table in tables:
    cursor.execute(f'SELECT COUNT(*) FROM {table}')
    count = cursor.fetchone()[0]
    total_rows += count
    print(f'{table}: {count} lignes')

print(f"\nTotal: {total_rows} lignes")
conn.close()
