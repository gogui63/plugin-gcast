touch /tmp/dependancy_gcast_in_progress
echo 0 > /tmp/dependancy_gcast_in_progress
echo "********************************************************"
echo "*             Installation des dépendances             *"
echo "********************************************************"
apt-get update
echo 50 > /tmp/dependancy_gcast_in_progress
echo 65 > /tmp/dependancy_gcast_in_progress
echo 75 > /tmp/dependancy_gcast_in_progress
echo 85 > /tmp/dependancy_gcast_in_progress
echo 100 > /tmp/dependancy_gcast_in_progress
echo "********************************************************"
echo "*             Installation terminée                    *"
echo "********************************************************"
rm /tmp/dependancy_gcast_in_progress