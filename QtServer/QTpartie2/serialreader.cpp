#include "SerialReader.h"
#include <QDebug>
#include <Qtime>
#include <QDate>
#include <QtSql/QSqlQuery>
#include <QDebug>
#include <QThread>


SerialReader::SerialReader(QObject* parent) : QObject(parent), gpsData(""), db(QSqlDatabase::addDatabase("QMYSQL")) {
    configureSerialPort();
    configureDatabase();
    connect(&serialPort, &QSerialPort::readyRead, this, &SerialReader::onReadyRead);
    QTimer::singleShot(1000, this, &SerialReader::onReadyRead);
}

SerialReader::~SerialReader() {
    if (db.isOpen()) {
        db.close();
    }
}

void SerialReader::onReadyRead() {
    QByteArray data = serialPort.readAll();
    if (!data.isEmpty()) {
        gpsData += data;
        processGpsData();
    }

    QThread::msleep(1000); 
}


void SerialReader::configureSerialPort() {
    serialPort.setPortName("COM3");
    serialPort.setBaudRate(QSerialPort::Baud9600);
    serialPort.setDataBits(QSerialPort::Data8);
    serialPort.setParity(QSerialPort::NoParity);
    serialPort.setStopBits(QSerialPort::OneStop);
    serialPort.setFlowControl(QSerialPort::NoFlowControl);
    if (serialPort.open(QIODevice::ReadOnly)) {
        qDebug() << "Port serie ouvert.";
    }
    else {
        //qWarning() << "Impossible d'ouvrir le port serie : " << serialPort.errorString();
    }
}

void SerialReader::configureDatabase() {
    QSqlDatabase db = QSqlDatabase::addDatabase("QMYSQL");
    db.setHostName("192.168.64.157");
    db.setDatabaseName("BDD");
    db.setUserName("root");
    db.setPassword("root");
    if (db.open()) {
        qDebug() << "Connexion reussie a " << db.hostName() << " base de données.";
    }
    else {
        //qWarning() << "La connexion a la base de donnees a echoue : " << db.lastError().text();
    }
}

void SerialReader::processGpsData() {
    QStringList sentencesList = QString(gpsData).split('\n');
    QList<QByteArray> sentences;
    for (const QString& sentence : sentencesList) {
        QByteArray byteArray = sentence.toLocal8Bit();
        sentences.append(byteArray);
    }

    for (const QByteArray& sentence : sentences) {
        if (sentence.startsWith("$GPGGA")) {
            QList<QByteArray> fields = sentence.split(',');
            if (fields.size() >= 10) {
                QByteArray latitude = fields[2];
                QByteArray rawLongitude = fields[4];
                double longitudeValue = rawLongitude.toDouble();
                QString longitude = QString::number(longitudeValue, 'f', 6);
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
                else {
                    //qWarning() << "Erreur d'insertion dans la base de données : " << query.lastError().text();
                }
            }
        }
    }

    QTimer::singleShot(1000, this, &SerialReader::onReadyRead);
}
