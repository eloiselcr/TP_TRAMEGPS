#ifndef SERIALREADER_H
#define SERIALREADER_H

#include <QtCore/QObject>
#include <QtSerialPort/QSerialPort>
#include <QtSql/QSqlDatabase>
#include <QtSql/QSqlQuery>
#include <QTime>
#include <QStringList>
#include <QTimer>

class SerialReader : public QObject {
    Q_OBJECT

public:
    SerialReader(QObject* parent = nullptr);
    virtual ~SerialReader();

public slots:
    void onReadyRead();

private:
    QSerialPort serialPort;
    QString gpsData;
    QSqlDatabase db;

    void configureSerialPort();
    void configureDatabase();
    void processGpsData();
};

#endif // SERIALREADER_H
