#!/bin/bash
BROKER="mqtt.iut-blagnac.fr"
DB_PASS="passroot"

CAPTEURS=$(/opt/lampp/bin/mysql -u root -p"$DB_PASS" sae23 -sN -e "SELECT nom_capteur, nom_salle, type FROM capteur;")

echo "$CAPTEURS" | while read -r nom salle type; do
    type_fmt=$(echo "$type" | tr '[:upper:]' '[:lower:]' | sed 's/é/e/g; s/è/e/g')
    TOPIC="sandbox/student/iut/bate/etage2/$salle/$type_fmt"
    
    valeur=$(mosquitto_sub -h "$BROKER" -p 8883 -u student -P student -t "$TOPIC" -C 1 2>/dev/null | jq '.value' 2>/dev/null)
    
    if [ ! -z "$valeur" ] && [ "$valeur" != "null" ]; then
        DATE=$(date "+%Y-%m-%d")
        HEURE=$(date "+%H:%M:%S")
        /opt/lampp/bin/mysql -u root -p"$DB_PASS" sae23 -e "INSERT INTO mesure (date, horaire, valeur, nom_capteur) VALUES ('$DATE', '$HEURE', $valeur, '$nom');"
    fi
done
