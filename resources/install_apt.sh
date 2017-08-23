PROGRESS_FILE=/tmp/dependancy_rfplayer_in_progress
if [ ! -z $1 ]; then
	PROGRESS_FILE=$1
fi
touch ${PROGRESS_FILE}
echo 0 > ${PROGRESS_FILE}
echo "********************************************************"
echo "*             Installation des dépendances             *"
echo "********************************************************"
apt-get update
echo 50 > ${PROGRESS_FILE}
apt-get remove -y python-enum
echo 60 > ${PROGRESS_FILE}
apt-get install -y  python-requests python-serial python-pyudev 
echo 75 > ${PROGRESS_FILE}
pip install enum-compat
echo 100 > ${PROGRESS_FILE}
echo "********************************************************"
echo "*             Installation terminée                    *"
echo "********************************************************"
rm ${PROGRESS_FILE}
