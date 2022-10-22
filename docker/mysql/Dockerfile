FROM mysql:8
RUN echo "Creado directorio para copia de base de datos"
RUN mkdir /sistemahrodb/
RUN echo "Etableciendo directorio de trabajo"
WORKDIR /sistemahrodb
RUN echo "Copiando base de datos"
COPY pacientes_init.sql .
RUN echo "Restaurando base de datos"