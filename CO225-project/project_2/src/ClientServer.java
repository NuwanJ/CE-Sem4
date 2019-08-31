import java.net.Socket;

public interface ClientServer {

    static final int WAIT_NAME = 0;
    static final int WAIT_SYMBOL = 1;
    static final int ALLOW_BID = 2;

    String clientName = null;
    String clientSymbol = null;

    Socket mySocket = null;

    boolean handleConnection(Socket socket);

    boolean authSymbol(String symbol);

}
