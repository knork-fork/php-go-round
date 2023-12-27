#!/usr/bin/env bash

SCRIPT_DIR="$(dirname "$(readlink -f "$0")")"

rm -f "$SCRIPT_DIR/output.jtl"

echo "Running 1000 samples (100 concurrent) to localhost/"

jmeter -n -t "$SCRIPT_DIR/basic_test_plan.jmx" -l "$SCRIPT_DIR/output.jtl" > /dev/null 2>&1
awk -F, '{sum+=$2; count++} END {print "Average Response Time: " sum/count " ms"}' "$SCRIPT_DIR/output.jtl"

# Complex throughput calculation (chat gpt generated, possibly buggy, but mostly matches jmeter GUI)
awk -F, '
    NR>1 {
        if (min == 0 || $1 < min) {min = $1} # Find the earliest start time
        current_end = $1 + $2; # Calculate the end time for this request
        if (current_end > max) {max = current_end} # Update the latest end time if this request ends later
    }
    END {
        totalTime = (max - min) / 1000; # Calculate total time in seconds
        if (totalTime > 0) {
            throughput = 1000 / totalTime * 60; # Calculate throughput as requests per minute
            print "Total Time (Seconds): " totalTime;
            print "Throughput (Requests/Minute): " throughput;
        } else {
            print "Error in calculating total time";
        }
    }
' "$SCRIPT_DIR/output.jtl"
