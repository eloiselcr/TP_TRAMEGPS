#include "database.h"
#include <QSqlDatabase>
#include <QSqlQuery>
#include <QSqlError>
#include <QCoreApplication>
#include <QtSerialPort/QSerialPort>
#include <QtSql/QtSql>
#include <QDebug>
#include <QThread>
#include <QObject>
#include <QTime>

Database::Database()
{
    // Configuration de la connexion à la base de données MySQL
    QSqlDatabase db = QSqlDatabase::addDatabase("QMYSQL"); // ou mettre QSQLITE pour SQLite
    db.setHostName("192.168.64.157");
    db.setUserName("root");
    db.setPassword("root");
    db.setDatabaseName("BDD"); // ou mettre le nom du fichier sqlite

    if (db.isOpen()) {
        qDebug() << "c good";
    }
    else {
        qDebug() << "pas good";

    }
}

bool Database::open()
{
    return db.open();
}

void Database::close()
{
    db.close();
}

bool Database::insertGpsData(const QString& time, const QByteArray& latitude, const QByteArray& longitude)
{
    if (db.isOpen()) {
        qDebug() << "c good";

        QSqlQuery query;
        QString queryString = "INSERT INTO GPS (Longitude, Latitude) VALUES ('" + longitude + "', '" + latitude + "')";
        query.exec(queryString);

        if (query.lastError().isValid()) {
            qWarning() << "Erreur d'insertion dans la base de données : " << query.lastError().text();
        }
        else {
            return true;
        }
    }
    else {
        qWarning() << "Impossible de se connecter à la base de données.";
    }
    return false;
}

