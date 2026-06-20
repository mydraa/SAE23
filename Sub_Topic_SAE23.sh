#!/bin/bash

# List of rooms to monitor
salles=("E208" "E210" "C001" "C102")

for salle in "${salles[@]}"; do
    tmpfile="./${salle}_tempTEMP.txt"
    outfile="./${salle}_temp.txt"

    # Subscribe to the room's topic and extract the temperature value
    mosquitto_sub -t sensors/AM107/by-room/${salle}/data -h mqtt.iut-blagnac.fr -u student -P student -C 1 -p 8883 \
        | jq '.[0].temperature' >> "$tmpfile"

    # Count the number of recorded values
    lignes=$(wc -l < "$tmpfile")

    # Initialize max and min with the first value
    max=$(head -n 1 "$tmpfile")
    min=$(head -n 1 "$tmpfile")
    moyenne=0

    # Loop through all values to find max, min and compute the sum
    for ((i=1; i<=lignes; i++)); do
        val=$(head -n $i "$tmpfile" | tail -n 1)
        if (( min > val )); then
            (( min = val ))
        elif (( max < val )); then
            (( max = val ))
        fi
        (( moyenne = moyenne + val ))
    done

    # Write results to the output file
    echo "=== $salle ===" > "$outfile"
    echo "Latest value : $(tail -n 1 "$tmpfile")" >> "$outfile"
    echo "Max : $max" >> "$outfile"
    echo "Min : $min" >> "$outfile"
    echo "Average : $(( moyenne / lignes ))" >> "$outfile"

done