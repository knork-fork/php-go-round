package main

import (
    "log"
    "net/http"
    "os/exec"
    "time"
    "os"
)

func handler(w http.ResponseWriter, r *http.Request) {
    start := time.Now() // Start timer

    route := r.URL.Path
    log.Printf("Route resolved: %s\n", time.Since(start)) // Log time for route resolution

    // Construct the command to execute the PHP script
    cmd := exec.Command("php", "/app/public/index.php", route)

    start = time.Now() // Reset timer
    // Execute the PHP script and capture its output.
    output, err := cmd.CombinedOutput()
    log.Printf("PHP script executed: %s\n", time.Since(start)) // Log time for PHP script execution

    if err != nil {
        // If there's an error executing the script, write the error to the response
        w.WriteHeader(http.StatusInternalServerError)
        w.Write(output) // Output contains the error message from PHP
        log.Printf("PHP error: %s, Output: %s\n", err, output) // Log both the error and the PHP output
        return
    }

    start = time.Now() // Reset timer
    // Write the output of the script to the HTTP response
    w.Write(output)
    log.Printf("Response written: %s\n", time.Since(start)) // Log time for writing the response
}

func main() {
    // Setup log file
    logFile, err := os.OpenFile("/var/log/go_debug.log", os.O_CREATE|os.O_WRONLY|os.O_APPEND, 0666)
    if err != nil {
        log.Fatalf("Failed to open log file: %v", err)
    }
    defer logFile.Close()

    // Set log output to the file
    log.SetOutput(logFile)

    http.HandleFunc("/", handler)
    log.Fatal(http.ListenAndServe(":8080", nil))
}
