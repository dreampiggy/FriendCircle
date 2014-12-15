import sys
import os
import time

def addTimeStamp(rootDir, time, lasttimestamp):
	for dir in os.listdir(rootDir) :
		path = os.path.join(rootDir, dir)
		print path
		if os.path.isdir(path) :
			addTimeStamp(path, time, lasttimestamp)
		else :
			html = open(path, "r").read()
			newhtml = html
			if("?t="+str(lasttimestamp) in newhtml) :
				newhtml = newhtml.replace(".js?t="+str(lasttimestamp), ".js?t="+str(time))
				newhtml = newhtml.replace(".css?t="+str(lasttimestamp), ".css?t="+str(time))
			else :
				newhtml = newhtml.replace(".js", ".js?t="+str(time))
				newhtml = newhtml.replace(".css", ".css?t="+str(time))
			file = open(path, "w")
			file.write(newhtml)
			file.close()

if __name__ == '__main__':

	lastTimeStamp = open("./lasttimestamp", "r").readline()
	rootDir = '.\FriendCircle\Tpl'
	time = int(time.time())

	file =  open("./lasttimestamp", "w")
	file.write(str(time))
	file.close()
	addTimeStamp(rootDir, time, lastTimeStamp)
