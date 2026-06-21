#!/bin/bash
BROKER="mqtt.iut-blagnac.fr"
DB_PASS="passroot"

# Retrieve nom_capteur, room name (E208) and sensor type from database
CAPTEURS=$(/opt/lampp/bin/mysql -u root -p"$DB_PASS" sae23 -sN -e "SELECT nom_capteur, nom_salle, type FROM capteur;")

echo "$CAPTEURS" | while read -r nom_capteur salle type; do
    # Format the type string (e.g. 'temperature' or 'humidite' without uppercase or accents)
    type_fmt=$(echo "$type" | tr '[:upper:]' '[:lower:]' | sed 's/é/e/g; s/è/e/g')
    TOPIC="sandbox/student/iut/bate/etage2/$salle/$type_fmt"
    
    # Extract the value from the JSON object
    valeur=$(timeout 10 mosquitto_sub -h "$BROKER" -p 8883 -u student -P student -t "$TOPIC" -C 1 2>/dev/null < /dev/null | jq -r 'if has("temperature") then .temperature elif has("humidity") then .humidity elif has("value") then .value else .[0].temperature end' 2>/dev/null)
    
    # Check if the extracted value is not empty and not "null"
    if [ ! -z "$valeur" ] && [ "$valeur" != "null" ]; then
        DATE=$(date "+%Y-%m-%d")
        HEURE=$(date "+%H:%M:%S")
        
        # Insert into the database using the correct column name (nom_capteur)
        /opt/lampp/bin/mysql -u root -p"$DB_PASS" sae23 -e "INSERT INTO mesure (date, horaire, valeur, nom_capteur) VALUES ('$DATE', '$HEURE', $valeur, '$nom_capteur');"
    fi
done