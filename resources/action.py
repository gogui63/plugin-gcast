# -*- coding: utf-8 -*-
#!/usr/bin/env python
import sys
import json
import os
import time
import requests
import hashlib
import os.path
from gtts import gTTS
from pydub import AudioSegment
import subprocess

def pause():
    scriptdir=os.path.join(os.path.join(os.path.dirname(os.path.abspath(__file__)),'caster'),'stream2chromecast.py')
    generalparams = ' -devicename ' + sys.argv[2] + ' -pause'
    cmd = 'sudo /usr/bin/python ' +scriptdir + generalparams
    print cmd
    proc = subprocess.Popen([cmd], stdout=subprocess.PIPE, shell=True)
    (out, err) = proc.communicate()
    return out

def volup():
    scriptdir=os.path.join(os.path.join(os.path.dirname(os.path.abspath(__file__)),'caster'),'stream2chromecast.py')
    generalparams = ' -devicename ' + sys.argv[2] + ' -volup'
    cmd = 'sudo /usr/bin/python ' +scriptdir + generalparams
    print cmd
    proc = subprocess.Popen([cmd], stdout=subprocess.PIPE, shell=True)
    (out, err) = proc.communicate()
    return out

def voldown():
    scriptdir=os.path.join(os.path.join(os.path.dirname(os.path.abspath(__file__)),'caster'),'stream2chromecast.py')
    generalparams = ' -devicename ' + sys.argv[2] + ' -voldown'
    cmd = 'sudo /usr/bin/python ' +scriptdir + generalparams
    print cmd
    proc = subprocess.Popen([cmd], stdout=subprocess.PIPE, shell=True)
    (out, err) = proc.communicate()
    return out

def volume():
    scriptdir=os.path.join(os.path.join(os.path.dirname(os.path.abspath(__file__)),'caster'),'stream2chromecast.py')
    generalparams = ' -devicename ' + sys.argv[2] + ' -setvol ' + sys.argv[3]
    cmd = 'sudo /usr/bin/python ' +scriptdir + generalparams
    print cmd
    proc = subprocess.Popen([cmd], stdout=subprocess.PIPE, shell=True)
    (out, err) = proc.communicate()
    return out

def parle():
    cachepath=os.path.abspath(os.path.join(os.path.join(os.path.dirname(os.path.dirname(__file__)), 'tmp'), 'cache'))
    tmppath=os.path.abspath(os.path.join(os.path.dirname(os.path.dirname(__file__)), 'tmp'))
    try:
        os.stat(tmppath)
    except:
        os.mkdir(tmppath)
    file = hashlib.md5(sys.argv[3]+sys.argv[5]+sys.argv[6]).hexdigest()
    found = 0
    filename=os.path.join(cachepath,'tts.wav')
    filenamemp3=os.path.join(cachepath,file+'.mp3')
    if not os.path.isfile(filenamemp3):
        try:
            os.stat(cachepath)
        except:
            os.mkdir(cachepath)
        if sys.argv[6]== 'picotts':
            os.system('pico2wave -l '+sys.argv[5]+' -w '+filename+ ' "' +sys.argv[3]+ '"')
            song = AudioSegment.from_wav(filename)
        elif sys.argv[6]== 'webserver':
            filetts = requests.get('http://192.168.1.56:8089/?method=getTTS&text='+sys.argv[3]+'&voice=voxygen.tts.helene')
            output = open(filename,'wb')
            output.write(filetts.content)
            output.close()
            song = AudioSegment.from_wav(filename)
        else:
            tts = gTTS(text=sys.argv[3].decode('utf-8'), lang=sys.argv[5])
            tts.save(filenamemp3)
            song = AudioSegment.from_mp3(filenamemp3)
        song.export(filenamemp3, format="mp3", bitrate="128k", tags={'albumartist': 'Jeedom', 'title': 'TTS', 'artist':'Jeedom'}, parameters=["-ar", "44100","-vol", "200"])
    urltoplay=sys.argv[4]+'/plugins/gcast/tmp/cache/'+file+'.mp3'
    scriptdir=os.path.join(os.path.join(os.path.dirname(os.path.abspath(__file__)),'caster'),'stream2chromecast.py')
    generalparams = ' -devicename ' + sys.argv[2] + ' ' + filenamemp3 
    cmd = 'sudo /usr/bin/python ' +scriptdir + generalparams
    print cmd
    proc = subprocess.Popen([cmd], stdout=subprocess.PIPE, shell=True)
    (out, err) = proc.communicate()
    return out

actions = {
           "pause" : pause,
           "volup" : volup,
           "voldown" : voldown,
           "parle" : parle,
           "volume" : volume,
}
actions[sys.argv[1]]()
