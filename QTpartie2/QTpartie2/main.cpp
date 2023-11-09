#include <QtCore/QCoreApplication>
#include <QtSerialPort/QSerialPort>
#include <QtSql/QSqlDatabase>
#include <QtSql/QSqlQuery>
#include <QDebug>
#include <QTime>
#include <QStringList>
#include <QTimer>

class SerialReader : public QObject
{
    Q_OBJECT

public:
    SerialReader(QObject* parent = nullptr) : QObject(parent), gpsData(""), db(QSqlDatabase::addDatabase("QMYSQL"))
    {
        configureSerialPort();
        configureDatabase();
        connect(&serialPort, &QSerialPort::readyRead, this, &SerialReader::onReadyRead);
        QTimer::singleShot(1000, this, &SerialReader::onReadyRead); // D�marrer la lecture apr�s un d�lai initial d'une seconde
    }

    virtual ~SerialReader()
    {
        if (db.isOpen()) {
            db.close();
        }
    }

public slots:
    void onReadyRead()
    {
        QByteArray data = serialPort.readAll();
        if (!data.isEmpty()) {
            gpsData += data;
            processGpsData();
        }
    }

    void processGpsData()
    {
        QStringList sentencesList = QString(gpsData).split('\n');
        QList<QByteArray> sentences;

        for (const QString& sentence : sentencesList) {
            QByteArray byteArray = sentence.toLocal8Bit();
            sentences.append(byteArray);
        }

        for (const QByteArray& sentence : sentences) {
            if (sentence.startsWith("$GPGGA")) {
                // Analyse la trame NMEA GPGGA pour extraire la latitude et la longitude
                QList<QByteArray> fields = sentence.split(',');
                if (fields.size() >= 10) {
                    QByteArray latitude = fields[2];
                    QByteArray longitude = fields[4];

                    // Obtenir l'heure actuelle
                    QTime currentTime = QTime::currentTime();
                    QString timeString = currentTime.toString("hh:mm:ss");

                    qDebug() << "Time: " << timeString << ", Latitude: " << latitude << ", Longitude: " << longitude;

                    QSqlQuery query(db);
                    query.prepare("INSERT INTO GPS (Longitude, Latitude, Heure) VALUES (:longitude, :latitude, :time)");
                    query.bindValue(":longitude", QString(longitude));
                    query.bindValue(":latitude", QString(latitude));
                    query.bindValue(":time", timeString);

                    if (query.exec()) {
                        qDebug() << "Donn�es ins�r�es avec succ�s dans la base de donn�es.";
                    }
                    else {
                        //qWarning() << "Erreur d'insertion dans la base de donn�es : " << query.lastError().text();
                    }
                }
            }
        }

        // Ajoutez un d�lai d'une seconde avant de continuer la lecture
        QTimer::singleShot(1000, this, &SerialReader::onReadyRead);
    }

private:
    QSerialPort serialPort;
    QString gpsData;
    QSqlDatabase db;

    void configureSerialPort()
    {
        serialPort.setPortName("COM3");
        serialPort.setBaudRate(QSerialPort::Baud9600);
        serialPort.setDataBits(QSerialPort::Data8);
        serialPort.setParity(QSerialPort::NoParity);
        serialPort.setStopBits(QSerialPort::OneStop);
        serialPort.setFlowControl(QSerialPort::NoFlowControl);

        if (serialPort.open(QIODevice::ReadOnly)) {
            qDebug() << "Port s�rie ouvert.";
        }
        else {
            qWarning() << "Impossible d'ouvrir le port s�rie : " << serialPort.errorString();
        }
    }

    void configureDatabase()
    {
        db.setHostName("192.168.64.157");
        db.setUserName("root");
        db.setPassword("root");
        db.setDatabaseName("BDD");

        if (db.open()) {
            qDebug() << "Connexion r�ussie � " << db.hostName() << " base de donn�es.";
        }
        else {
            //qWarning() << "La connexion � la base de donn�es a �chou� : " << db.lastError().text();
        }
    }
};

int main(int argc, char* argv[])
{
    QCoreApplication a(argc, argv);

    SerialReader serialReader(&a);

    return a.exec();
}

#include "main.moc"
