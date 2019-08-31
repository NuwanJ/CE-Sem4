
public class RegisterFile {
	
	//Create the register file using an integer array
	static int[] regSet = new int[32];
	
	//Read data given the register address
	public static int getData(int addr) {
		return regSet[addr];
	}
	//Write data into a register
	public static void setData(int addr,int data) {
		regSet[addr] = data;
	}
	//For beq instructions, to remember return address
	public static void setReturnAddr(int addr) {
		regSet[31] = addr;
	}
}
