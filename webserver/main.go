package main

import (
    "log"
    "net/http"
    "os/exec"
)

func handler(w http.ResponseWriter, r *http.Request) {
    route := r.URL.Path

    // Construct the command to execute the PHP script
    cmd := exec.Command("php", "/app/src/index.php", route)

    // Execute the PHP script and capture its output.
    output, err := cmd.CombinedOutput()
    if err != nil {
        // If there's an error executing the script, write the error to the response
        http.Error(w, err.Error(), http.StatusInternalServerError)
        return
    }

    // Write the output of the script to the HTTP response
    w.Write(output)
}

func main() {
    http.HandleFunc("/", handler)
    log.Fatal(http.ListenAndServe(":8080", nil))
}
