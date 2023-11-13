#include <QtCore/QCoreApplication>
#include "SerialReader.h"

int main(int argc, char* argv[]) {
    QCoreApplication a(argc, argv);
    SerialReader serialReader;
    return a.exec();
}
