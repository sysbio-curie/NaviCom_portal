#!/usr/bin/python3
#-*- coding:utf8 -*-

import os, sys
sys.path.append("/home/ubuntu/bin/binPython") # Access to the downloaded curie.navicell
import re, time
import cgi
import cgitb
cgitb.enable() # Debug for development
sys.tracebacklimit=0

from navicom import *

# Redirect to the page
#if __name__ == '__main__':
    #print("Content-type: text/html")
    #print("Location: ./bridge.php?log_msg=NaviCell%20session%20initialized,%20data%20are%20loading\r\n")

def log(log_entry):
    with open("navicom_log", "a") as ff:
        ff.write(time.strftime("%H:%M %d/%m/%Y", time.localtime()) + " ")
        ff.write(str(log_entry) + "\r\n")
    

form = cgi.FieldStorage()
log(form)

# Die if the fields set by the javascript are not present
# Other solution would be to use Location: error page
if "study_selection" in form:
    study_id = form["study_selection"].value
    dname = os.popen("Rscript getData.R " + study_id) # TODO maybe, use the gmt file from the map
else:
    print("Content-type: text/html;charset=utf-8\n\n")
    raise ValueError("Error: no study selected\n")

if "action" in form:
    action = form["action"].value
    for ll in dname.readlines():
        if (re.match("^FNAME:", ll)):
            fname = re.sub("^FNAME: ", ll)
            break
else:
    action = "none"
    print("Content-type: text/html;charset=utf-8\n\n")
    raise ValueError("Error: no action selected\n")

# Headers
#print("Content-type: text/plain;charset=utf-8\n\n")
#print(headers)

if (action == "download"):
    #print "Content-type:application/octet-stream; name=\"FileName\"\r\n";
    #print "Content-Disposition: attachment; filename=\"FileName\"\r\n\n";
    print(fname)
elif (action == "display"):
    displayMethod = form["display_selection"].value
    mm = [bool(re.search("[dD]isplay", list(NaviCom.__dict__.keys())[ii] )) for ii in range(len(NaviCom.__dict__.keys()))]
    valid_displays = list(np.array(NaviCom.__dict__.keys())[np.array(mm)])
    assert displayMethod in valid_displays, "This method of display does not exist"

    session_id = form["id"].value
    url = form["url"].value
    nc = NaviCom(name=data_name)
    nc._attachSession(url, session_id)
    nc.loadData(fname)

    processing = form["processing"].value
    if (displayMethod == "completeDisplay"):
        nc.completeDisplay(processing = processing)
    elif (displayMethod == "displayMethylome"):
        nc.displayMethylome(processing=processing) # background = "auto" ?
    elif (displayMethod == "displayMutations"):
        nc.displayMutations()
    print(fname)
else:
    print("Invalid action")


