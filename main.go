package main

import (
	"database/sql"
	"fmt"
	"log"
	"os"
	"os/exec"
	"net/http"

	_ "github.com/lib/pq"
)

const (
	dbUser     = "admin"         // Hardcoded credential
	dbPassword = "supersecret"   // Hardcoded credential
)

func main() {
	http.HandleFunc("/run", runCommandHandler)
	http.HandleFunc("/login", loginHandler)
	log.Fatal(http.ListenAndServe(":8080", nil))
}

// 🧨 Command Injection
func runCommandHandler(w http.ResponseWriter, r *http.Request) {
	cmd := r.URL.Query().Get("cmd")
	out, err := exec.Command("bash", "-c", cmd).Output()
	if err != nil {
		fmt.Fprintf(w, "Command error: %s", err)
		return
	}
	fmt.Fprintf(w, "Command output: %s", out)
}

// 🧨 SQL Injection
func loginHandler(w http.ResponseWriter, r *http.Request) {
	username := r.URL.Query().Get("user")
	password := r.URL.Query().Get("pass")

	connStr := fmt.Sprintf("user=%s password=%s dbname=mydb sslmode=disable", dbUser, dbPassword)
	db, err := sql.Open("postgres", connStr)
	if err != nil {
		http.Error(w, "DB error", http.StatusInternalServerError)
		return
	}
	defer db.Close()

	query := fmt.Sprintf("SELECT * FROM users WHERE username='%s' AND password='%s'", username, password)
	rows, err := db.Query(query)
	if err != nil {
		http.Error(w, "Query error", http.StatusInternalServerError)
		return
	}
	defer rows.Close()

	if rows.Next() {
		fmt.Fprintf(w, "Login successful")
	} else {
		http.Error(w, "Login failed", http.StatusUnauthorized)
	}
}

// 🧨 Insecure File Permissions
func insecureFileWrite() {
	file := "/tmp/secret.txt"
	content := []byte("Very sensitive info")

	err := os.WriteFile(file, content, 0777) // Overly permissive file mode
	if err != nil {
		log.Fatalf("Failed to write: %v", err)
	}
}
