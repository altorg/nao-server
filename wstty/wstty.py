# pip install twisted six autobahn
# twistd -y wstty.py

LAUNCHER = ["/opt/nethack/nethack.alt.org/dgamelaunch"]
#ENDPOINT = "unix:/tmp/wstty"
ENDPOINT = "tcp:port=9000:interface=127.0.0.1"
#ENDPOINT = "tcp:port=9000:interface=127.0.0.1"
#ENDPOINT = "ssl:port=9090:privateKey=cert.key:certKey=cert.pem"

from twisted.internet import protocol, reactor, task
from autobahn.twisted import websocket
from twisted.python import log
import signal, fcntl, termios, array, pty, os

class WSTTYProcess(protocol.ProcessProtocol):
    def __init__(self, other):
        self.other = other
    def outReceived(self, data):
        self.other.sendMessage(data, isBinary=True)
    def errReceived(self, data):
        self.other.sendMessage(data, isBinary=True)
    def processEnded(self, reason):
        try: self.other.sendClose()
        except: pass

class WSTTYProtocol(websocket.WebSocketServerProtocol):
    def onConnect(self, request):
        self.peer = request.peer
        if "x-forwarded-for" in request.headers:
            self.peer += " [%s]" % (request.headers["x-forwarded-for"])

        log.msg("new connection from " + self.peer)

        try:
            c = int(request.params["c"][0])
            l = int(request.params["l"][0])
            if c and l:
                self.winsize = array.array("H", [l, c, 0, 0])
        except:
            self.winsize = None

        self.keepalive = task.LoopingCall(self.sendPing)

    def onOpen(self):
        log.msg("opened connection from " + self.peer)

        masterfd, slavefd = pty.openpty()
        ttyname = os.ttyname(slavefd)

        try:
            if self.winsize is not None:
                # fucking magnets, how do they work?
                fcntl.ioctl(masterfd, termios.TIOCSWINSZ, self.winsize)
        except:
            pass

        self.other = WSTTYProcess(self)
        reactor.spawnProcess(self.other, LAUNCHER[0], LAUNCHER,
                             env={}, usePTY=(masterfd, slavefd, ttyname))

        self.keepalive.start(30)

    def onMessage(self, payload, isBinary):
        self.keepalive.reset()

        if isBinary:
            self.other.transport.write(payload)

    def onPong(self, payload):
        self.keepalive.reset()

    def onClose(self, wasClean, code, reason):
        log.msg("closed connection from " + self.peer)

        try:
            self.keepalive.stop()
        except: pass

        try: self.other.transport.signalProcess(signal.SIGHUP)
        except: pass

if __name__ == "__builtin__":
    from twisted.application import service, internet
    from twisted.internet import endpoints
    application = service.Application("wstty")
    internet.StreamServerEndpointService(
        endpoints.serverFromString(reactor, ENDPOINT),
        websocket.WebSocketServerFactory.forProtocol(WSTTYProtocol)
    ).setServiceParent(application)
