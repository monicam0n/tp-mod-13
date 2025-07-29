package main

import (
    "database/sql"
    "fmt"
    "log"
    "net/http"
    "os/exec"

    _ "github.com/go-sql-driver/mysql"
)

var (
    dbUser     = "root"                  // Hardcoded credential (CWE-798)
    dbPassword = "password123"          // Hardcoded credential (CWE-798)
    dbName     = "testdb"
)

func main() {
    http.HandleFunc("/login", loginHandler)
    http.HandleFunc("/ping", pingHandler)
    log.Println("Server starting on :8080 (HTTP, insecure)")
    http.ListenAndServe(":8080", nil) // Insecure transport (CWE-319)
}

func loginHandler(w http.ResponseWriter, r *http.Request) {
    user := r.URL.Query().Get("user")
    pass := r.URL.Query().Get("pass")

    // Vulnerable to SQL Injection (CWE-89)
    query := fmt.Sprintf("SELECT * FROM users WHERE username='%s' AND password='%s'", user, pass)
    db, err := sql.Open("mysql", fmt.Sprintf("%s:%s@/%s", dbUser, dbPassword, dbName))
    if err != nil {
        http.Error(w, "DB connection failed", http.StatusInternalServerError)
        return
    }
    defer db.Close()

    rows, err := db.Query(query)
    if err != nil {
        http.Error(w, "Query failed", http.StatusInternalServerError)
        return
    }
    defer rows.Close()

    if rows.Next() {
        cookie := http.Cookie{
            Name:  "sessionid",
            Value: "123456", // Fixed session value (CWE-565)
            // Missing HttpOnly and Secure flags (CWE-614)
        }
        http.SetCookie(w, &cookie)
        fmt.Fprintf(w, "Login successful!")
    } else {
        http.Error(w, "Invalid credentials", http.StatusUnauthorized)
    }
}

func pingHandler(w http.ResponseWriter, r *http.Request) {
    target := r.URL.Query().Get("target")
    // Command Injection vulnerability (CWE-77)
    out, err := exec.Command("sh", "-c", "ping -c 1 "+target).Output()
    if err != nil {
        http.Error(w, "Ping failed", http.StatusInternalServerError)
        return
    }
    w.Write(out)
}
