import pexpect

IP = '172.23.54.205'
scriptPath = ':/root/test.xlsx'
localPath = 'demo.txt'
password = 'By2GYH'
ssh_newkey='Are you sure you want to continue connecting'


def login(p):
        print "\n Sending Password to sit net machine..."
        p.sendline(password)
        print "\nPassword Sent."
        p.expect(['.*#',pexpect.EOF])



def scpScripts():
        try:
            print "\nDoing scp of load data spreadsheet"
            child=pexpect.spawn('scp -rq '+localPath+' root@'+IP+scriptPath,timeout=120)
            i=child.expect(['Password:','.*[#/$]',pexpect.EOF],timeout=300)
            if i==0:
                        login(child)
            if i==1:
                        pass
            if i==3:
                       print "\nI either got key or connection timeout"
        except Exception ,e:
           print "\n Not able to login and do the Upgrade configuration "
           print"\n",str(e)
           print "\n Exiting  the Script"

        print "\n SCP Complete."
        child.terminate

if __name__ == "__main__":
 
    scpScripts()

