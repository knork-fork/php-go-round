# Start from a PHP image
FROM php:8.3-cli

# Install Go
RUN apt-get update && apt-get install -y wget
RUN wget https://golang.org/dl/go1.16.linux-amd64.tar.gz
RUN tar -xvf go1.16.linux-amd64.tar.gz
RUN mv go /usr/local
ENV PATH="/usr/local/go/bin:${PATH}"

WORKDIR /app

COPY main.go .
RUN go build -o main main.go

CMD ["/app/main"]