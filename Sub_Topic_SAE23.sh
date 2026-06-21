#!/bin/bash
BROKER="mqtt.iut-blagnac.fr"
DB_PASS="passroot"

while true; do
    CAPTEURS=$(/opt/lampp/bin/mysql -u root -p"$DB_PASS" sae23 -sN -e "SELECT nom_capteur, nom_salle, type FROM capteur;")

    echo "$CAPTEURS" | while read -r nom_capteur salle type; do
        # Format the type string (e.g. 'temperature' or 'humidite' without uppercase or accents)
        type_fmt=$(echo "$type" | tr '[:upper:]' '[:lower:]' | sed 's/é/e/g; s/è/e/g')
        TOPIC="sensors/AM107/by-room/$salle/data"
        
        # Fetch the JSON payload
        JSON_DATA=$(timeout 10 mosquitto_sub -h "$BROKER" -p 8883 -u student -P student -t "$TOPIC" -C 1 2>/dev/null < /dev/null)
        
        # Extract the correct value depending on the sensor type
        if [ "$type_fmt" = "temperature" ]; then
            valeur=$(echo "$JSON_DATA" | jq -r '.[0].temperature' 2>/dev/null)
        elif [ "$type_fmt" = "humidite" ]; then
            valeur=$(echo "$JSON_DATA" | jq -r '.[0].humidity' 2>/dev/null)
        else
            valeur=$(echo "$JSON_DATA" | jq -r ".[0].$type_fmt" 2>/dev/null)
        fi
        
        # Check if the extracted value is not empty and not "null"
        if [ ! -z "$valeur" ] && [ "$valeur" != "null" ]; then
            DATE=$(date "+%Y-%m-%d")
            HEURE=$(date "+%H:%M:%S")
            
            # Insert into the database using the correct column name (nom_capteur)
            /opt/lampp/bin/mysql -u root -p"$DB_PASS" sae23 -e "INSERT INTO mesure (date, horaire, valeur, nom_capteur) VALUES ('$DATE', '$HEURE', $valeur, '$nom_capteur');"
        fi
    done
    
    echo "Waiting 30 seconds before next collection..."
    sleep 30
done