#include <Bounce2.h>
#include "ESP8266HTTPClient.h"
#include "ESP8266WiFi.h"

int hourTaster = 16;
int minuteTaster = 5;
int sendTaster = 4;

int stunde = 0;
int minute = 0;

int serialCoutner = 0;

Bounce hourButton = Bounce();
Bounce minuteButton = Bounce();
Bounce sendButton = Bounce();

const char* ssid = "Gehirnablage 4.0";
const char* password = "+50cminnach840Pete";

void setup() {
  Serial.begin(9600);
  Serial.println("System gestartet");

  WiFi.begin(ssid, password); 
  
  while (WiFi.status() != WL_CONNECTED) {
      delay(1000);
      Serial.println("Connecting to WiFi..");
  }

  Serial.println("Connected to the WiFi network");


  // input mode
  pinMode(hourTaster, INPUT);
  pinMode(minuteTaster, INPUT);
  pinMode(sendTaster, INPUT);

  hourButton.attach(hourTaster);
  hourButton.interval(5);

  minuteButton.attach(minuteTaster);
  minuteButton.interval(5);

  sendButton.attach(sendTaster);
  sendButton.interval(5);

}

void loop() {
  hourButton.update();
  minuteButton.update();
  sendButton.update();

  if (hourButton.fallingEdge()) {
    addHour();
  }

  if (minuteButton.fallingEdge()) {
    add15Minutes();
  }

  if(sendButton.fallingEdge()){
    Serial.print("Upload initialized... ");
    HTTPClient http;
    WiFiClientSecure client;  
    client.setInsecure();

    String serverPath = 
    String("https://jtbnet.de/msbopen/index.php") + 
    String("?msbopenhour=") + 
    String(stunde) + 
    String("&msbopenminute=") + 
    String(minute);

    http.begin(client, serverPath);

    int httpCode = http.GET();
    if (httpCode != 200)
    {
      Serial.println("Upload schief gelaufen... ");
      Serial.println(http.getString());
    }
    else
    {
      Serial.println("Upload scheint erfolgreich.");
    }
    writeToSerial();
  }

  delay(10);
}

void addHour()
{
  if(stunde == 23)
  {
    stunde = 0;
  }
  else
  {
    stunde++;
  }
  writeToSerial();
}

void add15Minutes()
{
  if(minute == 45)
  {
    minute = 0;
  }
  else
  {
    minute = minute + 15;
  }
  writeToSerial();
}

void writeToSerial()
{
  Serial.print("Stunde: ");
  Serial.print(stunde);
  Serial.print(" | Minute: ");
  Serial.print(minute);
  Serial.println();
}