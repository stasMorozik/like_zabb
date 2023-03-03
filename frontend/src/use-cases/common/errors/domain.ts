import { Error } from "./error";

export namespace Domain {
  export class InvalidEmail implements Error {
    constructor(
      readonly message: string
    ){}
  }

  export class InvalidPassword implements Error {
    constructor(
      readonly message: string
    ){}
  }

  export class InvalidName implements Error {
    constructor(
      readonly message: string
    ){}
  }
}
