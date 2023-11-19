#include "SerialReader.h"
#include <QDebug>
#include <Qtime>
#include <QDate>
#include <QtSql/QSqlQuery>
#include <QDebug>
#include <QThread>


SerialReader::SerialReader(QObject* parent) : QObject(parent), gpsData(""), db(QSqlDatabase::addDatabase("QMYSQL")) {
    // Configuration du port série et de la base de données
    configureSerialPort();
    configureDatabase();
     // Connexion du signal readyRead du port série
    connect(&serialPort, &QSerialPort::readyRead, this, &SerialReader::onReadyRead);
    // Lancement de la lecture des données une seconde
    QTimer::singleShot(1000, this, &SerialReader::onReadyRead);
}

SerialReader::~SerialReader() {
    if (db.isOpen()) {
        db.close();
    }
}

void SerialReader::onReadyRead() {
 // Lecture des données du port série
    QByteArray data = serialPort.readAll();
    if (!data.isEmpty()) {
        // Ajout des données lues à la chaîne gpsData
        gpsData += data;
        processGpsData();
    }
    QThread::msleep(1000); 
}


void SerialReader::configureSerialPort() {
    // Configuration des paramètres du port série
    serialPort.setPortName("COM3");
    serialPort.setBaudRate(QSerialPort::Baud9600);
    serialPort.setDataBits(QSerialPort::Data8);
    serialPort.setParity(QSerialPort::NoParity);
    serialPort.setStopBits(QSerialPort::OneStop);
    serialPort.setFlowControl(QSerialPort::NoFlowControl);
    if (serialPort.open(QIODevice::ReadOnly)) {
        qDebug() << "Port serie ouvert.";
    }
}

void SerialReader::configureDatabase() {
    // Configuration des paramètres de la base de données
    QSqlDatabase db = QSqlDatabase::addDatabase("QMYSQL");
    db.setHostName("192.168.64.157");
    db.setDatabaseName("BDD");
    db.setUserName("root");
    db.setPassword("root");
    if (db.open()) {
        qDebug() << "Connexion reussie a " << db.hostName() << " base de données.";
    }
}

void SerialReader::processGpsData() {
    // Séparation des trames GPS dans la chaîne gpsData
    QStringList sentencesList = QString(gpsData).split('\n');
    QList<QByteArray> sentences;
    for (const QString& sentence : sentencesList) {
        QByteArray byteArray = sentence.toLocal8Bit();
        sentences.append(byteArray);
    }
 // Traitement de chaque trames GPS
    for (const QByteArray& sentence : sentences) {
        if (sentence.startsWith("$GPGGA")) {
            // Séparation des champs de la trame GPS
            QList<QByteArray> fields = sentence.split(',');
            if (fields.size() >= 10) {
                // Extraction des données de latitude, longitude et heure
                QByteArray rawLatitude = fields[2];
                QString latitude = QString::fromUtf8(rawLatitude).remove(QRegExp("^50"));
                QByteArray rawLongitude = fields[4];
                QString longitude = QString::fromUtf8(rawLongitude).remove(QRegExp("^0*1"));
                QTime currentTime = QTime::currentTime();
                QString timeString = currentTime.toString("hh:mm:ss");
                qDebug() << "Time: " << timeString << ", Latitude: " << latitude << ", Longitude: " << longitude;

                QSqlQuery query(db);
                query.prepare("INSERT INTO GPS (Longitude, Latitude, Heure) VALUES (:longitude, :latitude, :time)");
                query.bindValue(":longitude", QString(longitude));
                query.bindValue(":latitude", QString(latitude));
                query.bindValue(":time", timeString);

                if (query.exec()) {
                    qDebug() << "Donnees inserees avec succes dans la base de donnees.";

                }
            }
        }
    }
  // Relance de la lecture des données une seconde après le traitement
    QTimer::singleShot(1000, this, &SerialReader::onReadyRead);
}

