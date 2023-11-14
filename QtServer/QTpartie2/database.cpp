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
    // Configuration de la connexion � la base de donn�es MySQL
    QSqlDatabase db = QSqlDatabase::addDatabase("QMYSQL"); // ou mettre QSQLITE pour SQLite
    db.setHostName("192.168.65.195");
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
            qWarning() << "Erreur d'insertion dans la base de donn�es : " << query.lastError().text();
        }
        else {
            return true;
        }
    }
    else {
        qWarning() << "Impossible de se connecter � la base de donn�es.";
    }
    return false;
}

